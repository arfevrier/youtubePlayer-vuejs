<template>
  <v-card
  class="mx-auto"
  max-width="600"
  >
    <v-card-item>
      <v-card-title>{{ title }}</v-card-title>
      <v-card-subtitle>{{ id }}</v-card-subtitle>
    </v-card-item>

    <v-divider></v-divider>
    
    <v-card-text>
      <div class="text-center">
          <audio controls="" width="300" :src="source"></audio>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    props: ['title','source','id'],
  }
</script>