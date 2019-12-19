<template>
  <div class="card col-md-6">
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
          <label for="category" class="col-md-4 col-form-label text-md-right">Category</label>

          <div class="col-md-6">
            <multiselect @input="updateSubcategoryList" v-model="program.parentCategory" :options="parentCategories" label="name"></multiselect>
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

    <div id="error-msg" v-if="$v.program.title.$invalid && submitted">
      Preencha o campo de titulo.
    </div>
  </div>
</template>

<script>
  import { required } from 'vuelidate/lib/validators';

  export default {
    name: 'programFormComponent',
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
    validations: {
      program: {
        title: { required }
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
        this.program.file = event.target.files[0]
      },
      async submit() {
        this.submitted = true;

        if (this.$v.program.title.$invalid) {
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
          this.$refs.file.value = '';
          window.location.replace('/programs');
        }
      },
    },
    watch: {
      program: {
        handler(val, oldVal) {
          this.submitted = false;
        },
        deep: true
      },
    }
  }
</script>
