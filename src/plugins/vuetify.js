import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
          light: {
            primary: 'f44336',
            secondary: '#b0bec5',
          },
        },
      },
    
});
