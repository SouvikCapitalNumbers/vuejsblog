<template>
	<div>
		<Header :userdata="userdata" :loading="loading"></Header>
		<div class="container mt-5">
			<div class="row mb-2">
			   <div class="col-md-8">
			      <div class="card flex-md-row mb-4 box-shadow h-md-250">
			        <div class="card-body d-flex flex-column align-items-start">
			            <div class="form-group col-12">
							<label>Title</label>
							<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Title" v-model="form.title">
						</div>
						<div class="form-group col-12 mt-5">
							<label>Description</label>
							<editor
								v-model="form.description"
						       	api-key="d5qclqbihg70ney5lxjn328rel4ngxagmugpmqz5kmo7dnm6"
						       :init="{
						         height: 500,
						         menubar: false,
						         plugins: [
						           'advlist autolink lists link image charmap print preview anchor',
						           'searchreplace visualblocks code fullscreen',
						           'insertdatetime media table paste code help wordcount'
						         ],
						         toolbar:
						           'undo redo | formatselect | bold italic backcolor | \
						           alignleft aligncenter alignright alignjustify | \
						           bullist numlist outdent indent | removeformat | help'
						       }"
						    />
						</div>
			        </div>
			      </div>
			   	</div>
			   	<div class="col-md-4">
			      	<div class="card flex-md-row mb-4 box-shadow h-md-250 mr-1">
			        	<div class="card-body d-flex flex-column align-items-start">
			        		<button type="button" class="btn btn-outline-primary col-12" @click="createBlog">{{buttonText}}</button>
			        		<div class="col-12 mt-5">
			        			<div class="custom-control custom-checkbox col-8">
									<input type="checkbox" class="custom-control-input" v-model="form.show_related">
									<label class="custom-control-label">Show Related Post</label>
								</div>
			        		</div>
			        		<div class="col-12 mt-3 pl-2">
				        		<image-uploader
							        :preview="true"
							        :maxWidth="380"
							        :className="['fileinput', { 'fileinput--loaded': hasImage }]"
							        capture="environment"
							        :debug="1"
							        doNotResize="gif"
							        :autoRotate="true"
							        outputFormat="base64"
							        @input="setImage"
							      >
							        <label for="fileInput" slot="upload-label">
							          <figure>
							            <svg
							              xmlns="http://www.w3.org/2000/svg"
							              width="32"
							              height="32"
							              viewBox="0 0 32 32"
							            >
							              <path
							                class="path1"
							                d="M9.5 19c0 3.59 2.91 6.5 6.5 6.5s6.5-2.91 6.5-6.5-2.91-6.5-6.5-6.5-6.5 2.91-6.5 6.5zM30 8h-7c-0.5-2-1-4-3-4h-8c-2 0-2.5 2-3 4h-7c-1.1 0-2 0.9-2 2v18c0 1.1 0.9 2 2 2h28c1.1 0 2-0.9 2-2v-18c0-1.1-0.9-2-2-2zM16 27.875c-4.902 0-8.875-3.973-8.875-8.875s3.973-8.875 8.875-8.875c4.902 0 8.875 3.973 8.875 8.875s-3.973 8.875-8.875 8.875zM30 14h-4v-2h4v2z"
							              ></path>
							            </svg>
							          </figure>
							          <span class="upload-caption">{{
							            hasImage ? "Replace" : "Click to upload"
							          }}</span>
							        </label>
						    	</image-uploader>
						    </div>
						    <div class="col-12 mt-3 pl-2" v-if="imageUrl">
						    	<img width="380" :src="imageUrl" />
						    </div>
			        	</div>
			        </div>
			    </div>
			</div>

		</div>
	</div>
</template>

<script>
	import Header from '../common/Header.vue'
	import axios from 'axios'
	import Editor from '@tinymce/tinymce-vue'
	import ImageUploader from 'vue-image-upload-resize'

	export default {
		name: "BlogCreate",
		components: { Header, Editor, ImageUploader },
		data: () => {
			return {
				userdata: null,
				form: {
					id: 0,
					title: '',
					description: '',
					image: '',
					show_related: false
				},
				msg: "Vue Image Upload and Resize Demo",
			    hasImage: false,
			    image: null,
			    loading: false,
			    imageUrl: null,
			    buttonText: 'Create'
			}
		},
		created(){
			document.title = 'Create New Blog';
			this.loadPage();
			if(this.$route.params.id)
			{
				this.getBlogData(this.$route.params.id);
			}
		},
		methods: {
			loadPage() {
				this.loading = true;
				axios.get('/api/user')
				.then((res) => {
					this.userdata = res.data.data;
					this.loading = false;
				})
				.catch((error) => {
					localStorage.setItem('token', '');
					this.$router.push('/login');
					this.loading = false;
				});
			},

			getBlogData(id) {
				axios.get('/api/blog/edit/'+id)
				.then((res) => {
					this.form.id = res.data.data.id
					this.form.title = res.data.data.title
					this.form.description = res.data.data.description
					this.form.show_related = res.data.data.show_related
					this.imageUrl = res.data.data.image
					this.loading = false;
					this.buttonText = 'Edit';
				})
				.catch((error) => {
					this.$fire({
						title: 'Blog Edit error',
						text: 'You are not author of this Blog',
						type: "error",
						timer: 5000
					});
					this.$router.push('/');
					this.loading = false;
				});
			},

			setImage(output) {
				this.hasImage = true;
				this.image = output;
				this.form.image = output;
				this.imageUrl = false;
			},

			createBlog()
			{
				console.log(this.form);
				if(this.form.title.length < 5)
				{
					this.$fire({
						title: 'Blog create error',
						text: 'Title must be a valid title minimum 50 charecters required',
						type: "error",
						timer: 5000
					});
				}
				else if(this.form.description.length < 50)
				{
					this.$fire({
						title: 'Blog Description error',
						text: 'For creating blog a minimum 50 charecters required',
						type: "error",
						timer: 5000
					});
				}
				else
				{
					console.log(this.form);
					axios.post('/api/blog/store', this.form)
                 	.then((res) => {
                 		this.$fire({
							title: 'Done',
							text: res.data.message,
							type: "success",
							timer: 5000
						});
	                    this.$router.push('/my-blogs');
                 	})
                 	.catch((error) => {
                 		if(error.response.data.message == 'Validation Error')
	                 	{
		                 	let errorMsg = error.response.data.errors.toString();
		                    this.$fire({
								title: error.response.data.message,
								text: errorMsg,
								type: "error",
								timer: 5000
							});
		                }
		                else
		                {
		                	this.$fire({
								title: 'Error Occured!',
								text: error.response.data.message,
								type: "error",
								timer: 5000
							});
		                }
                 	});
				}
			}
		}
	}

</script>

<style>
#fileInput {
  display: none;
}
h1,
h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
.my-8 {
  margin-top: 4rem;
  margin-bottom: 4rem;
}
</style>