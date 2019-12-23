<template>
  <ValidationObserver v-slot="{ handleSubmit }"> 
    <form @submit.prevent="handleSubmit(submit)">
      <input type="hidden" name="_token" :value="token" />

      <div class="form-group row">
        <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="episode.title" id="title" type="text" class="form-control" name="title" autocomplete="title">
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
          <label for="title" class="col-md-4 col-form-label text-md-right">Program</label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <multiselect v-model="episode.programId" :options="programs" label="title"></multiselect>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="file" class="col-md-4 col-form-label text-md-right">Audio</label>

        <div class="col-md-6">
          <input ref="audio" name="file" type="file" @change="setFile($event)" />
        </div>
      </div>

      <div class="form-group row">
        <label for="cover" class="col-md-4 col-form-label text-md-right">Cover</label>

        <div class="col-md-6">
          <input ref="cover" name="cover" type="file" @change="setCover($event)" />
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
          <button :disabled="submitted" class="btn btn-primary">
            {{ buttonTitle }}
          </button>
          <span v-if="submitted">Carregando</span>
        </div>
      </div>
    </form>
  </ValidationObserver>
</template>

<script>
  import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
  import { required } from 'vee-validate/dist/rules';

  extend('required', {
      ...required,
      message: 'This field is required'
  });

  export default {
    name: 'episodeFormComponent',
    props: [
      'data',
      'isEdit',
      'programs',
      'route',
      'token'
    ],
    components: {
      ValidationProvider,
      ValidationObserver
    },
    data() {
      return {
        episode: {
          title: null,
          programId: null,
          file: null,
          cover: null,
          description: null
        },
        submitted: false,
        instanceRoute: this.route,
        form: new FormData()
      }
    },
    computed: {
      buttonTitle() {
        if ((!this.episode.programId || !this.episode.file || !this.episode.cover || !this.episode.description) && (this.data.episodes || this.data.audio)) {
          return 'Salvar como rascunho'
        }

        return 'Salvar'
      }
    },
    created() {
      if (this.data) {
          this.episode.title = this.data.title;
          this.episode.programId = this.data.program_id;
          this.episode.description = this.data.description;

          this.episode.programId = this.programs.filter(program => {
            return program.id == this.data.program_id
          }).pop()
      }

      if (this.isEdit) {
        this.instanceRoute += `/${this.data.id}`;  
        this.form.append('_method', 'PUT');
      }
    },
    methods: {
      clearSubmitted() {
        if (this.episode.programId != '') {
          this.submitted = false;
        }
      },
      setFile(event) {
        this.form.delete('file')
        this.episode.file = event.target.files[0]
        event.target.files = null;
      },
      setCover(event) {
        this.form.delete('cover')
        this.episode.cover = event.target.files[0]
        event.target.files = null;
      },
      getAudio(blob) {
        return new Promise((resolve, reject) => {
          let audio = new Audio(blob);
          audio.addEventListener('loadedmetadata', () => resolve(audio));
        });
      },
      async submit() {
        this.submitted = true;

        let blob;
        let audio;
        if (this.episode.file) {
          blob = window.URL.createObjectURL(this.episode.file);
          audio = await this.getAudio(blob);

          this.form.append('duration', audio.duration);
          this.form.append('type', this.episode.file.type);
          this.form.append('size', this.episode.file.size);
          this.form.append('file', this.episode.file);
        }

        this.form.append('_token', this.token);
        this.form.append('title', this.episode.title);
        this.form.append('program_id', this.episode.programId.id);

        this.form.append('cover', this.episode.cover);
        this.form.append('description', this.episode.description);

        let response = await window.axios.post(this.instanceRoute, this.form);

        if (response) {
          this.form.delete('_method');
          this.instanceRoute = this.route;

          this.form = new FormData();

          this.$refs.audio.value = '';
          this.$refs.cover.value = '';

          for(let key in this.episode) {
            this.episode[key] = null
          }

          window.location.replace('/episodes');
          this.submitted = false;
        }
      }
    }
  }
</script>
