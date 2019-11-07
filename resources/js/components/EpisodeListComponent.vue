<template>
  <div class="card">
    <div v-for="(program, programIndex) in programs" class="card-body">
      <h5 class="card-title">{{ program.title }}</h5>
      <!-- <img :src="episode.image" class="img-fluid" style="width: 100px;" alt="Responsive image"> -->

      <ul class="list-group" v-if="program.episodes.length > 0">
        <li v-for="(episode, episodeIndex) in program.episodes" class="list-group-item">
          {{ episode.title }}

          <audio ref="player" controls>
            <source :src="episode.audio" type="audio/mpeg">
            Your browser does not support the audio element.
          </audio> 

          <span @click="msgHandler(programIndex, episodeIndex, 'delete', episode)" style="float: right; cursor: pointer;">&nbsp; Delete</span>
          <span @click="msgHandler(programIndex, episodeIndex, 'put', episode)" style="float: right; cursor: pointer;">Edit</span>

          <span v-if="episode.toggleOperation" style="float: right;">
            <span @click="confirm(programIndex, episodeIndex, episode)" :class="colorClass">confirm</span> | <span @click="msgHandler(programIndex, episodeIndex, 'cancel', episode)">cancel</span> - &nbsp;
          </span> 
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  import { eventBus } from '../eventBus';

  export default {
    props: [
      'programsProp',
      'route'
    ],
    data() {
      return {
        programs: this.programsProp,
        operationMethod: '',
        colorClass: '',
        lastOperationClicked: ''
      }
    },
    created() {
    },
    mounted() {
      eventBus.$on('updateEpisodeList', (res) => {
        if (this.lastOperationClicked == 'put') {
          this.programs = res.data
          this.$refs.player.forEach(audio => audio.load())
          return 
        }

        this.programs.forEach(program => {
          if (program.id == res.data.program_id) {
            program.episodes.push(res.data)
          }
        })
      });
    },
    methods: {
      msgHandler(programIndex, episodeIndex, operationMethod, episode) {
        if (operationMethod == 'cancel') {
          this.programs[programIndex].episodes[episodeIndex].toggleOperation = false
          eventBus.$emit('setAddForm', true);
          return 
        }

        if (operationMethod != this.lastOperationClicked) {
          this.operationMethod = operationMethod
          this.colorClass = operationMethod == 'delete' ? 'text-danger' : 'text-warning'
          this.lastOperationClicked = operationMethod;
        }

        if (!episode.toggleOperation) {
          this.programs[programIndex].episodes[episodeIndex].toggleOperation = !episode.toggleOperation
        }
      },
      confirm(programIndex, episodeIndex, episode) {
        if (this.operationMethod == 'put') {
          return eventBus.$emit('populateForm', episode);
        }

        window.axios.delete(`${this.route}/${episode.id}`)
          .then(res => {
            let index = this.programs[programIndex].episodes.indexOf(episode)
            this.programs[programIndex].episodes.splice(index, 1)
          });
      }
    },
  }
</script>
