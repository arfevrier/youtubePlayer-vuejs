<template>
  <v-card
  class="mx-auto"
  max-width="600"
  >
  <v-card-item>
    <v-card-title>
      <v-avatar
      :color="color" 
      size="32"
      >
      </v-avatar>
      #{{ index }} {{ user }}
    </v-card-title>
    <v-card-subtitle>{{ date }}</v-card-subtitle>
  </v-card-item>

    <v-divider></v-divider>

    <v-card-text>
      <div class="text--primary">{{ message }}</div>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    props: ['user','message', 'index', 'color', 'date'],
  }
</script>