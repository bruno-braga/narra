<template>
  <form @submit.prevent="submit">
    <input type="hidden" name="_token" :value="token" />

    <!-- <div class="form-group row">
      <label for="language" class="col-md-4 col-form-label text-md-right">Language</label>

      <div class="col-md-6">
        <input v-model="setting.language" id="language" type="text" class="form-control" name="language" autocomplete="language" autofocus>
      </div>
    </div> -->

    <div class="form-group row">
      <label for="title" class="col-md-4 col-form-label text-md-right">Language</label>

      <div class="col-md-6">
        <select class="form-control" v-model="setting.language_id">
          <option disabled value="">Choose a program</option>
          <option v-for="language in languages" :value="language.id">{{ language.country }}</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="copyright" class="col-md-4 col-form-label text-md-right">Copyright</label>

      <div class="col-md-6">
        <input v-model="setting.copyright" id="copyright" type="text" class="form-control" name="copyright" autocomplete="copyright" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="explicit" class="col-md-4 col-form-label text-md-right">Explicit</label>

      <div class="col-md-6">
        <input v-model="setting.explicit" id="copyright" type="checkbox" name="explicit" autocomplete="explicit" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="subtitle" class="col-md-4 col-form-label text-md-right">Subtitle</label>

      <div class="col-md-6">
        <input v-model="setting.subtitle" id="subtitle" type="text" class="form-control" name="subtitle" autocomplete="subtitle" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="author" class="col-md-4 col-form-label text-md-right">Author</label>

      <div class="col-md-6">
        <input v-model="setting.author" id="author" type="text" class="form-control" name="author" autocomplete="author" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="owner_name" class="col-md-4 col-form-label text-md-right">Owener Name</label>

      <div class="col-md-6">
        <input v-model="setting.owner_name" id="owner_name" type="text" class="form-control" name="owner_name" autocomplete="owner_name" autofocus>
      </div>
    </div>

    <!-- <div class="form-group row">
        <label for="title" class="col-md-4 col-form-label text-md-right">Program</label>

      <div class="col-md-6">
        <select class="form-control" v-model="setting.category">
          <option disabled value="">Choose a program</option>
          <option v-for="category in categories" :value="category">{{ category }}</option>
        </select>
      </div>
    </div> -->

    <!-- <div class="form-group row">
      <label for="item_author" class="col-md-4 col-form-label text-md-right">Item Author</label>

      <div class="col-md-6">
        <input v-model="setting.item_author" id="item_author" type="text" class="form-control" name="item_author" autocomplete="item_author" autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label for="item_mp3_length" class="col-md-4 col-form-label text-md-right">Item mp3 length</label>

      <div class="col-md-6">
        <input v-model="setting.item_mp3_length" id="item_mp3_length" type="text" class="form-control" name="item_mp3_length" autocomplete="item_mp3_length" autofocus>
      </div>
    </div> -->

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
    name: 'settingsFormComponent',
    props: [
      'data',
      'languages',
      'isEdit',
      'program',
      'route',
      'token'
    ],
    data() {
      return {
        setting: {
          language_id: null,
          copyright: null,
          subtitle: null,
          author: null,
          owner_name: null
        },
        submitted: false,
        instanceRoute: this.route,
        form: new FormData()
      }
    },
    validations: {
    },
    created() {
    },
    methods: {
      clearSubmitted() {
        if (this.episode.programId != '') {
          this.submitted = false;
        }
      },
      async submit() {
        let response = await window.axios.post(this.route, { settings: this.setting });

        if (response) {
          console.log(response);
        }
      }
    }
  }
</script>
