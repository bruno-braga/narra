<template>
  <div class="card col-md-6" style="padding: 0px">
    <div class="card-header">Create a program</div>

    <div class="card-body">
      <ValidationObserver v-slot="{ handleSubmit }"> 
        <form @submit.prevent="handleSubmit(submit)" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="token" />

          <div class="form-group row">
            <label for="title" class="col-md-4 col-form-label text-md-right">Title <span>*</span></label>

            <div class="col-md-6">
              <ValidationProvider rules="required">
                <div slot-scope="{ errors }">
                  <input v-model="program.title" id="title" type="text" class="form-control" name="title" autocomplete="title">
                  <p>{{ errors[0] }}</p>
                </div>
              </ValidationProvider>
            </div>
          </div>

          <div class="form-group row">
            <label for="category" class="col-md-4 col-form-label text-md-right">Category <span>*</span></label>

            <div class="col-md-6">
              <ValidationProvider rules="required">
                <div slot-scope="{ errors }">
                  <multiselect @input="updateSubcategoryList" v-model="program.parentCategory" :options="parentCategories" label="name"></multiselect>
                  <p>{{ errors[0] }}</p>
                </div>
              </ValidationProvider>
            </div>
          </div>

          <div class="form-group row">
            <label for="category" class="col-md-4 col-form-label text-md-right">Subcategory</label>

            <div class="col-md-6">
              <multiselect
                  :multiple="true"
                  v-model="program.childCategory"
                  :options="childCategoryOptions"
                  label="name"
                  :close-on-select="false" 
                  :clear-on-select="false" 
                  :hide-selected="true" 
                  track-by="name" 
                  :preselect-first="true"
              >
              </multiselect>
            </div>
          </div>


          <div class="form-group row">
            <label for="ile" class="col-md-4 col-form-label text-md-right">Cover <span>*</span></label>

            <div class="col-md-6">
              <input name="file" type="file" ref="file" @change="setFile($event)" />
              <div v-if="(inputFile.touched || submitted) && !isEdit && !program.file">
                This field is required.
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="description" class="col-md-4 col-form-label text-md-right">Description <span>*</span></label>

            <div class="col-md-6">
              <ValidationProvider rules="required">
                <div slot-scope="{ errors }">
                  <textarea v-model="program.description" name="description"></textarea>
                  <p>{{ errors[0] }}</p>
                </div>
              </ValidationProvider>
            </div>
          </div>

          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button :disabled="submitted" class="btn btn-primary" @click="fileValidation">
                Submit
              </button>
              <span v-if="submitted">Carregando</span>
            </div>
          </div>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
  import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
  import { required } from 'vee-validate/dist/rules';

  extend('required', {
      ...required,
      message: 'This field is required'
  });

  export default {
    name: 'programFormComponent',
    components: {
      ValidationProvider,
      ValidationObserver
    },
    props: [
      'parentCategories',
      'childCategories',
      'isEdit',
      'data',
      'route',
      'token'
    ],
    created() {
      if (this.data) {
        this.program.title = this.data.title;
        this.program.description = this.data.description;

        this.program.parentCategory = this.data.categories.filter(category => {
          return category.parent_id == null
        }).pop()

        this.program.childCategory = this.data.categories.filter(category => {
          if (category.parent_id != null) {
            return category
          }
        })
      }

      if (this.isEdit) {
        this.instanceRoute += `/${this.data.id}`;  
        this.form.append('_method', 'PUT');
      }
    },
    data() {
      return {
        inputFile: {
          touched: false,
        },
        childCategoryOptions: [],
        program: {
          title: null,
          parentCategory: '',
          childCategory: '',
          file: null,
          description: null
        },
        submitted: false,
        instanceRoute: this.route, 
        form: new FormData()
      }
    },
    methods: {
      updateSubcategoryList(option) {
        this.childCategoryOptions = this.childCategories
          .filter(category => {
            return category.parent_id == this.program.parentCategory.id
          })
      },
      setFile(event) {
        this.form.delete('file')
        this.program.file = event.target.files[0]
      },
      fileValidation() {
        this.inputFile.touched = true;
      },
      async submit() {
        this.submitted = true;

        if (!this.program.file && !this.isEdit) {
          return
        }

        let childCategories;
        if (this.program.childCategory) {
          this.form.delete('category_id[]');

          childCategories = this.program.childCategory.map(category => (category.id));
          childCategories.forEach(id => this.form.append('category_id[]', id));
        }

        this.form.append('_token', this.token);
        this.form.append('title', this.program.title);
        this.form.append('category_id[]', this.program.parentCategory.id);

        this.form.append('file', this.program.file);
        this.form.append('description', this.program.description);

        let response = await window.axios.post(this.instanceRoute, this.form);

        if (response) {
          this.submitted = false;
          this.$refs.file.value = '';
          window.location.replace('/programs');
        }
      },
    }
  }
</script>

<style>
  label > span {
    color: red;
  }
</style>
