export default {
    youtubeGetID(link){
        const regex = /(?:http(?:s?):\/\/(?:www\.|m\.)?youtu(?:(?:be\.com\/watch\?v=|\.be\/)|(?:be\.com\/(?:shorts|live)\/)|(?:be\.com\/v\/))([\w\-_]*)(?:(?:&.*)|(?:\?.*))?)|([\w\-_]+)/g;
        let m;
        while ((m = regex.exec(link)) !== null) {
            if (m.index === regex.lastIndex) {
                regex.lastIndex++;
            }
            if(m[1] != undefined){
                return m[1];
            }
            if(m[2] != undefined){
                return m[2];
            }
        }
        return;
    },
    getRandomColor(){
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    },
}
