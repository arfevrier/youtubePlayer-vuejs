<template>
    <v-card
    class="mx-auto"
    max-width="600"
    >

    <v-card-title>{{ title }}</v-card-title>

    <v-card-subtitle>{{ id }}</v-card-subtitle>

    <v-divider></v-divider>

    <v-card-text>
      <div class="text-center">
          <video controls="" width="300" :src="source"></video>
      </div>
    </v-card-text>
    <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn icon @click.stop="$emit('showComment', id)">
                <v-icon color="red">mdi-message-text-fast</v-icon>
              </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    props: ['title','source','id'],
  }
</script>