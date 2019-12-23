<template>
  <div style="clear: right">
    <div v-for="(program, programIndex) in programs">
      <br>

      <h5>{{ program.title }}</h5>

      <br>

      <ul class="list-group" v-if="program.episodes.length > 0">
        <li v-for="(episode, episodeIndex) in program.episodes" class="list-group-item">
          {{ episode.title }}

          <span class="badge badge-secondary" style="margin: 5px 0 0 5px">{{ episode.is_draft ? "Draft" : "" }}</span>

          <img v-if="episode.images" :src="episode.images.path" class="img-fluid" style="width: 100px;" alt="Responsive image">

          <audio ref="player" controls v-if="!episode.is_draft">
            <source :src="episode.audios.path" type="audio/mpeg">
            Your browser does not support the audio element.
          </audio> 


          <div class="float-right">
            <a :href="`/episodes/${episode.slug}/edit`" style="cursor: pointer;">Edit</a> <b>|</b>
            <span @click="msgHandler(episodeIndex, 'delete')" class="delete-span" style="cursor: pointer;">Delete</span>

            <span v-if="msgHandlerArray[episodeIndex]" class="float-left">
              <span
                @click="confirm(programIndex, episodeIndex, episode)"
                class="text-danger">
                  confirm
              </span> |

              <span
                @click="msgHandler(episodeIndex, 'cancel')">
                  cancel
              </span> - &nbsp;
            </span> 
          </div>
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
        let response = await window.axios.delete(`${this.route}/${episode.slug}`);

        if (response == 204) {
          let index = this.programs[programIndex].episodes.indexOf(episode)
          this.programs[programIndex].episodes.splice(index, 1)
        }
      }
    },
  }
</script>

<style>
.delete-span{
  color: #3490dc;
  text-decoration: none;
  background-color: transparent;
}

.delete-span:hover {
  text-decoration: underline;
}
</style>
