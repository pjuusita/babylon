

// https://stackoverflow.com/questions/29577909/how-can-i-copy-the-audio-data-from-a-wavesurfer-region-into-a-new-audio-buffer



export function paste(instance,cutSelection){
    var offlineAudioContext = instance.backend.ac
    var originalAudioBuffer = instance.backend.buffer;

    let cursorPosition = instance.getCurrentTime()
    var newAudioBuffer = offlineAudioContext.createBuffer(
        originalAudioBuffer.numberOfChannels,
        originalAudioBuffer.length + cutSelection.length,
        originalAudioBuffer.sampleRate);

    for (var channel = 0; channel < originalAudioBuffer.numberOfChannels;channel++) {

        var new_channel_data = newAudioBuffer.getChannelData(channel);
        var empty_segment_data = cutSelection.getChannelData(channel);
        var original_channel_data = originalAudioBuffer.getChannelData(channel);

        var before_data = original_channel_data.subarray(0, cursorPosition * originalAudioBuffer.sampleRate);
        var mid_data = empty_segment_data
        var after_data = original_channel_data.subarray(Math.floor(cursorPosition * originalAudioBuffer.sampleRate), (originalAudioBuffer.length * originalAudioBuffer.sampleRate));


        new_channel_data.set(before_data);
        new_channel_data.set(mid_data,(cursorPosition * newAudioBuffer.sampleRate));
        new_channel_data.set(after_data,(cursorPosition + cutSelection.duration)* newAudioBuffer.sampleRate);
    }
    return newAudioBuffer
}

export function cut(params,instance){
    /*
    ---------------------------------------------
    The function will take the buffer used to create the waveform and will
    create
    a new blob with the selected area from the original blob using the
    offlineAudioContext
    */

    // var self = this;
    var start = params.start;
    var end = params.end;

    var originalAudioBuffer = instance.backend.buffer;

    var lengthInSamples = Math.floor( (end - start) * originalAudioBuffer.sampleRate );
    if (! window.OfflineAudioContext) {
        if (! window.webkitOfflineAudioContext) {
            // $('#output').append('failed : no audiocontext found, change browser');
            alert('webkit context not found')
        }
        window.OfflineAudioContext = window.webkitOfflineAudioContext;
    }
    // var offlineAudioContext = new OfflineAudioContext(1, 2,originalAudioBuffer.sampleRate );
    var offlineAudioContext = instance.backend.ac

    var emptySegment = offlineAudioContext.createBuffer(
        originalAudioBuffer.numberOfChannels,
        lengthInSamples,
        originalAudioBuffer.sampleRate );

    var newAudioBuffer = offlineAudioContext.createBuffer(
        originalAudioBuffer.numberOfChannels,
        (start === 0 ? (originalAudioBuffer.length - emptySegment.length) :originalAudioBuffer.length),
        originalAudioBuffer.sampleRate);

    for (var channel = 0; channel < originalAudioBuffer.numberOfChannels;channel++) {

        var new_channel_data = newAudioBuffer.getChannelData(channel);
        var empty_segment_data = emptySegment.getChannelData(channel);
        var original_channel_data = originalAudioBuffer.getChannelData(channel);

        var before_data = original_channel_data.subarray(0, start * originalAudioBuffer.sampleRate);
        var mid_data = original_channel_data.subarray( start * originalAudioBuffer.sampleRate, end * originalAudioBuffer.sampleRate);
        var after_data = original_channel_data.subarray(Math.floor(end * originalAudioBuffer.sampleRate), (originalAudioBuffer.length * originalAudioBuffer.sampleRate));

        empty_segment_data.set(mid_data);
        if(start > 0){
            new_channel_data.set(before_data);
            new_channel_data.set(after_data,(start * newAudioBuffer.sampleRate));
        } else {
            new_channel_data.set(after_data);
        }
    }
    return {
        newAudioBuffer,
        cutSelection:emptySegment
    }

}

export function copy(region, instance){
    var segmentDuration = region.end - region.start

    var originalBuffer = instance.backend.buffer;
    var emptySegment = instance.backend.ac.createBuffer(
        originalBuffer.numberOfChannels,
        segmentDuration * originalBuffer.sampleRate,
        originalBuffer.sampleRate
    );
    for (var i = 0; i < originalBuffer.numberOfChannels; i++) {
        var chanData = originalBuffer.getChannelData(i);
        var emptySegmentData = emptySegment.getChannelData(i);
        var mid_data = chanData.subarray( region.start * originalBuffer.sampleRate, region.end * originalBuffer.sampleRate);
        emptySegmentData.set(mid_data);
    }

    return emptySegment
}

export function bufferToWave(abuffer, offset, len) {

    var numOfChan = abuffer.numberOfChannels,
        length = len * numOfChan * 2 + 44,
        buffer = new ArrayBuffer(length),
        view = new DataView(buffer),
        channels = [], i, sample,
        pos = 0;

    // write WAVE header
    setUint32(0x46464952);                         // "RIFF"
    setUint32(length - 8);                         // file length - 8
    setUint32(0x45564157);                         // "WAVE"

    setUint32(0x20746d66);                         // "fmt " chunk
    setUint32(16);                                 // length = 16
    setUint16(1);                                  // PCM (uncompressed)
    setUint16(numOfChan);
    setUint32(abuffer.sampleRate);
    setUint32(abuffer.sampleRate * 2 * numOfChan); // avg. bytes/sec
    setUint16(numOfChan * 2);                      // block-align
    setUint16(16);                                 // 16-bit (hardcoded in this demo)

    setUint32(0x61746164);                         // "data" - chunk
    setUint32(length - pos - 4);                   // chunk length

    // write interleaved data
    for(i = 0; i < abuffer.numberOfChannels; i++)
        channels.push(abuffer.getChannelData(i));

    while(pos < length) {
        for(i = 0; i < numOfChan; i++) {             // interleave channels
            sample = Math.max(-1, Math.min(1, channels[i][offset])); // clamp
            sample = (0.5 + sample < 0 ? sample * 32768 : sample * 32767)|0; // scale to 16-bit signed int
            view.setInt16(pos, sample, true);          // update data chunk
            pos += 2;
        }
        offset++                                     // next source sample
    }

    // create Blob
    return new Blob([buffer], {type: "audio/mpeg"});

    function setUint16(data) {
        view.setUint16(pos, data, true);
        pos += 2;
    }

    function setUint32(data) {
        view.setUint32(pos, data, true);
        pos += 4;
    }
}