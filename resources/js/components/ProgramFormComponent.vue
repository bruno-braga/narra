<template>
  <div class="card">
    <div class="card-header">Create a program</div>

    <div class="card-body">
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <input type="hidden" name="_token" :value="token" />

        <div class="form-group row">
          <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>

          <div class="col-md-6">
            <input v-model="program.title" id="title" type="text" class="form-control" name="title" autocomplete="title" autofocus>
          </div>
        </div>

        <div class="form-group row">
          <label for="file" class="col-md-4 col-form-label text-md-right">File</label>

          <div class="col-md-6">
            <input name="file" type="file" ref="file" @change="setFile($event)" />
          </div>
        </div>

        <div class="form-group row">
          <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

          <div class="col-md-6">
            <textarea v-model="program.description" name="description"></textarea>
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
    </div>
  </div>
</template>

<script>
  import { required } from 'vuelidate/lib/validators';

  export default {
    name: 'programFormComponent',
    props: [
      'isEdit',
      'data',
      'route',
      'token'
    ],
    created() {
      if (this.data) {
          this.program.title = this.data.title;
          this.program.description = this.data.description;
      }

      if (this.isEdit) {
        this.instanceRoute += `/${this.data.id}`;  
        this.form.append('_method', 'PUT');
      }
    },
    data() {
      return {
        program: {
          title: null,
          file: null,
          description: null
        },
        instanceRoute: this.route, 
        form: new FormData()
      }
    },
    validations: {
      title: { required },
      file: { required }
    },
    methods: {
      setFile(event) {
        this.program.file = event.target.files[0]
      },
      async submit() {
        this.form.append('_token', this.token);
        this.form.append('title', this.program.title);
        this.form.append('file', this.program.file);
        this.form.append('description', this.program.description);

        let response = await window.axios.post(this.instanceRoute, this.form);

        if (response) {
          this.$refs.file.value = '';
        }
      }
    }
  }
</script>
