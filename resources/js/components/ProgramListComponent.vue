<template>
  <div style="clear: right">
    <br>

    <ul class="list-group">
      <li v-for="(program, i) in programsFromProps" class="list-group-item">
        <a href="/episodes" class="float-left" style="cursor: pointer">
          <img v-if="program.images" :src="program.images.path" class="img-fluid img-thumbnail" alt="Responsive image" style="width: 100px;">
          {{ program.title }}
        </a>

        <spinner :show="loadingHandler[i]"></spinner>

        <div class="float-right">
          <a :href="`/programs/${program.slug}/edit`" class="delete-span" style="cursor: pointer;">Edit</a> <b>|</b>
          <span @click="msgHandler(i, 'delete')" class="delete-span" style="cursor: pointer;">Delete</span>

          <span v-if="msgHandlerArray[i]" class="float-left">
            <span @click="confirm(i, program)" class="text-danger">confirm</span> | <span @click="msgHandler(i, 'cancel')">cancel</span> - &nbsp;
          </span> 
        </div>
      </li>
    </ul>

    <div v-if="programsFromProps.length == 0">
      Click Add to start your podcast!
    </div>
  </div>
</template>

<script>
  export default {
    props: [
      'route',
      'programs'
    ],
    data() {
      return {
        programsFromProps: this.programs,
        msgHandlerArray: Array(this.programs.length).fill(false),
        loadingHandler: Array(this.programs.length).fill(false),
        operationMethod: '',
      }
    },
    methods: {
      msgHandler(i, operationMethod) {
        if (operationMethod == 'cancel') {
          this.setMsgHandler(i, false);
          return 
        }

        this.toggleMsgHandler(i);
      },
      toggleMsgHandler(i) {
        this.$set(this.msgHandlerArray, i, !this.msgHandlerArray[i])
      },
      setMsgHandler(i, val) {
        this.$set(this.msgHandlerArray, i, val);
      },
      async confirm(i, program) {
        let { data } = await window.axios.delete(`${this.route}/${program.id}`);

        this.$set(this.loadingHandler, i, !this.loadingHandler[i]);

        if (data == 204) {
          let index = this.programs.indexOf(program)
          this.$set(this.loadingHandler, i, !this.loadingHandler[i]);
          this.programs.splice(index, 1)

          this.loadingHandler.splice(i, 1);
          this.msgHandlerArray.splice(i, 1);
        }
      }
    }
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
