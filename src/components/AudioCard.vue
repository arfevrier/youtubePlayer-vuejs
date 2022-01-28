<template>
    <v-card
    class="mx-auto my-12"
    max-width="600"
    >

    <v-card-title>Audio</v-card-title>

   <v-card-subtitle>{{ title }}</v-card-subtitle>

    <v-card-text>
      <div class="text-center">
          <audio controls="" width="300" :src="source"></audio>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    props: ['title','source'],
  }
</script>