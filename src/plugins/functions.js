export default {
    youtubeGetID(link){
        const regex = /(http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-_]*))|([\w\-_]*)/g;
        let m;
        while ((m = regex.exec(link)) !== null) {
            if (m.index === regex.lastIndex) {
                regex.lastIndex++;
            }
            if(m[2] != undefined){
                return m[2];
            }
            if(m[3] != undefined){
                return m[3];
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
