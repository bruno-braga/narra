<template>
  <form @submit.prevent="submit">
    <input type="hidden" name="_token" :value="token" />

    <div class="form-group row">
      <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>

      <div class="col-md-6">
        <input v-model="episode.title" id="title" type="text" class="form-control" name="title" autocomplete="title" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="title" class="col-md-4 col-form-label text-md-right">Program</label>

      <div class="col-md-6">
        <select class="form-control" v-model="episode.programId">
          <option disabled value="">Choose a program</option>
          <option v-for="program in programs" :value="program.id">{{ program.title }}</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="file" class="col-md-4 col-form-label text-md-right">File</label>

      <div class="col-md-6">
        <input ref="audio" name="file" type="file" @change="setFile($event)" />
      </div>
    </div>

    <div class="form-group row">
      <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

      <div class="col-md-6">
        <textarea v-model="episode.description" name="description"></textarea>
      </div>
    </div>

    <div class="form-group row mb-0">
      <div class="col-md-6 offset-md-4">
        <button class="btn btn-primary">
          Submit
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import { required } from 'vuelidate/lib/validators';

  export default {
    name: 'episodeFormComponent',
    props: [
      'data',
      'isEdit',
      'programs',
      'route',
      'token'
    ],
    data() {
      return {
        episode: {
          title: null,
          programId: null,
          file: null,
          description: null
        },
        formTitle: 'add',
        instanceRoute: this.route,
        form: new FormData()
      }
    },
    created() {
      if (this.data) {
          this.episode.title = this.data.title;
          this.episode.programId = this.data.program_id;
          this.episode.description = this.data.description;
      }

      if (this.isEdit) {
        this.instanceRoute += `/${this.data.id}`;  
        this.form.append('_method', 'PUT');
      }
    },
    validations: {
      title: { required },
      file: { required }
    },
    methods: {
      setFile(event) {
        this.episode.file = event.target.files[0]
      },
      async submit() {
        this.form.append('_token', this.token);
        this.form.append('title', this.episode.title);
        this.form.append('program_id', this.episode.programId);
        this.form.append('file', this.episode.file);
        this.form.append('description', this.episode.description);

        let response = await window.axios.post(this.instanceRoute, this.form);

        if (response) {
          this.form.delete('_method');
          this.formTitle = 'add';
          this.instanceRoute = this.route;

          this.form = new FormData();
          this.$refs.audio.value = '';

          for(let key in this.episode) {
            this.episode[key] = null
          }
        }
      }
    }
  }
</script>
