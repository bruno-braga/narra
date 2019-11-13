<template>
  <div class="card">
    <div v-for="(program, programIndex) in programs" class="card-body">
      <h5 class="card-title">{{ program.title }}</h5>
      <!-- <img :src="episode.image" class="img-fluid" style="width: 100px;" alt="Responsive image"> -->

      <ul class="list-group" v-if="program.episodes.length > 0">
      <!-- <ul class="list-group" > -->
        <li v-for-object="(episode, episodeIndex) in program.episodes" class="list-group-item">
          {{ episode.title }}

          <audio ref="player" controls>
            <source :src="episode.audio" type="audio/mpeg">
            Your browser does not support the audio element.
          </audio> 

          <span @click="msgHandler(episodeIndex, 'delete')" style="float: right; cursor: pointer;">&nbsp; Delete</span>
          <a :href="`/episodes/${episode.id}/edit`" style="float: right; cursor: pointer;">Edit</a>

          <span v-if="msgHandlerArray[episodeIndex]" style="float: right;">
            <span @click="confirm(programIndex, episodeIndex, episode)" class="text-danger">confirm</span> | <span @click="msgHandler(episodeIndex, 'cancel')">cancel</span> - &nbsp;
          </span> 
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    props: [
      'programsProp',
      'route'
    ],
    data() {
      return {
        programs: this.programsProp,
        msgHandlerArray: Array(this.programsProp.length).fill(false),
      }
    },
    created() {
      console.log(this.programsProp)
    },
    methods: {
      msgHandler(programIndex, operationMethod) {
        if (operationMethod == 'cancel') {
          this.setMsgHandler(programIndex, false);
          return 
        }

        this.toggleMsgHandler(programIndex);
      },
      toggleMsgHandler(i) {
        this.$set(this.msgHandlerArray, i, !this.msgHandlerArray[i])
      },
      setMsgHandler(i, val) {
        this.$set(this.msgHandlerArray, i, val);
      },
      async confirm(programIndex, episodeIndex, episode) {
        let response = await window.axios.delete(`${this.route}/${episode.id}`);

        if (response == 204) {
          let index = this.programs[programIndex].episodes.indexOf(episode)
          this.programs[programIndex].episodes.splice(index, 1)
        }
      }
    },
  }
</script>
