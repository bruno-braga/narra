<template>
  <ul class="list-group">
    <li v-for="(program, i) in programsFromProps" class="list-group-item">
      <img :src="program.image" class="img-fluid img-thumbnail" alt="Responsive image" style="width: 100px;">
      {{ program.title }}

      <span @click="msgHandler(i, 'delete')" style="float: right; cursor: pointer;">&nbsp; Delete</span>
      <a :href="`/programs/${program.id}/edit`" style="float: right; cursor: pointer;">Edit</a>

      <span v-if="msgHandlerArray[i]" style="float: right;">
        <span @click="confirm(program)" :class="colorClass">confirm</span> | <span @click="msgHandler(i, 'cancel')">cancel</span> - &nbsp;
      </span> 
    </li>
  </ul>
</template>

<script>
  import { eventBus } from '../eventBus';

  export default {
    props: [
      'route',
      'programs'
    ],
    data() {
      return {
        programsFromProps: this.programs,
        msgHandlerArray: Array(this.programs.length).fill(false),
        operationMethod: '',
        colorClass: '',
        lastOperationClicked: ''
      }
    },
    mounted() {
      eventBus.$on('updateEpisodeList', (res) => {
        if (this.lastOperationClicked == 'put') {
          this.programsFromProps = res.data
          return 
        }

        this.programs.push(res.data)
      });
    },
    methods: {
      msgHandler(i, operationMethod) {
        if (operationMethod == 'cancel') {
          this.setMsgHandler(i, false);
          eventBus.$emit('setAddForm', true);
          return 
        }

        if (operationMethod != this.lastOperationClicked) {
          this.operationMethod = operationMethod
          this.colorClass = operationMethod == 'delete' ? 'text-danger' : 'text-warning'
          this.lastOperationClicked = operationMethod;
        }

        if (!this.msgHandlerArray[i]) {
          this.toggleMsgHandler(i);
        }
      },
      toggleMsgHandler(i) {
        this.$set(this.msgHandlerArray, i, !this.msgHandlerArray[i])
      },
      setMsgHandler(i, val) {
        this.$set(this.msgHandlerArray, i, val);
      },
      confirm(episode) {
        if (this.operationMethod == 'put') {
          return eventBus.$emit('populateForm', episode);
        }

        window.axios.delete(`${this.route}/${episode.id}`)
          .then(res => {
            let index = this.programs.indexOf(episode)
            this.programs.splice(index, 1)
          });
      }
    }
  }
</script>
