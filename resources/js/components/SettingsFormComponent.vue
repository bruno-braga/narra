<template>
  <ValidationObserver v-slot="{ handleSubmit }"> 
    <form @submit.prevent="submit">
      <input type="hidden" name="_token" :value="token" />

      <div class="form-group row">
        <label for="title" class="col-md-4 col-form-label text-md-right">Language <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <select class="form-control" v-model="setting.language_id">
                <option disabled value="">Choose a program</option>
                <option v-for="language in languages" :value="language.id">{{ language.country }}</option>
              </select>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="copyright" class="col-md-4 col-form-label text-md-right">Copyright <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="setting.copyright" id="copyright" type="text" class="form-control" name="copyright" autocomplete="copyright" autofocus>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="explicit" class="col-md-4 col-form-label text-md-right">Explicit <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="setting.explicit" id="copyright" type="checkbox" name="explicit" autocomplete="explicit" autofocus>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="subtitle" class="col-md-4 col-form-label text-md-right">Subtitle <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="setting.subtitle" id="subtitle" type="text" class="form-control" name="subtitle" autocomplete="subtitle" autofocus>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="author" class="col-md-4 col-form-label text-md-right">Author <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="setting.author" id="author" type="text" class="form-control" name="author" autocomplete="author" autofocus>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row">
        <label for="owner_name" class="col-md-4 col-form-label text-md-right">Owener Name <span style="color: red;">*</span></label>

        <div class="col-md-6">
          <ValidationProvider rules="required">
            <div slot-scope="{ errors }">
              <input v-model="setting.owner_name" id="owner_name" type="text" class="form-control" name="owner_name" autocomplete="owner_name" autofocus>
              <p>{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>
      </div>

      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button :disabled="submitted" class="btn btn-primary">
            Salvar
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
    name: 'settingsFormComponent',
    props: [
      'data',
      'languages',
      'isEdit',
      'program',
      'route',
      'token'
    ],
    components: {
      ValidationProvider,
      ValidationObserver
    },

    data() {
      return {
        setting: {
          language_id: null,
          explicit: null,
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
    created() {
      if (this.data) {
        Object.keys(this.setting).forEach(key => {
          this.setting[key] = this.data[key]
        });
      }
    },
    methods: {
      async submit() {
        this.submitted = true;
        let response = await window.axios.put(this.route, { settings: this.setting });

        if (response) {
          window.location.replace('/programs')
        }
      }
    }
  }
</script>
