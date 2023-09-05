<template>
	<div>
		<Header></Header>
		<section class="h-100">
			<div class="container h-100">
			  <div class="row justify-content-sm-center h-100">
			      <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
			      		<div class="text-center my-5"></div>
			          <div class="card shadow-lg">
			              <div class="card-body p-5">
			                  <h1 class="fs-4 card-title fw-bold mb-4">Registration</h1>
			                  <form method="POST" class="needs-validation" novalidate="" autocomplete="off" v-on:submit.prevent="submitForm">

			                  		<div class="mb-3">
			                          	<label class="mb-2 text-muted">Full Name</label>
			                          	<input type="text" class="form-control" v-model="form.name" value="" required autofocus />
			                       </div>
									<div class="mb-3">
									  	<label class="mb-2 text-muted" for="email">E-Mail Address</label>
									  	<input type="email" class="form-control" v-model="form.email" value="" required />
									</div>

			                      	<div class="mb-3">
			                          	<div class="mb-2 w-100">
			                              	<label class="text-muted">Password</label>
			                           	</div>
			                          	<input type="password" class="form-control" v-model="form.password" required />
			                      	</div>

			                      	<div class="d-flex align-items-center">
			                          	<button type="submit" class="btn btn-primary ms-auto">
			                              Register
			                          	</button>
			                      	</div>
			                  	</form>
			              </div>
			              <div class="card-footer py-3 border-0">
			                  <div class="text-center">Already have an account? <router-link to="/login" class="text-dark">Login</router-link></div>
			              </div>
			          </div>
			      </div>
			  </div>
			</div>
		</section>


	</div>
</template>


<script>

	import axios from 'axios';
	import Header from '../common/Header.vue';

	export default {
		name: "Register",
		components: { Header },
		data: () => {
			return {
				form: {
					name: '',
					email: '',
					password: ''
				}
			}
		},
		created: () => {
			document.title = 'Register and start blogging'
			console.log(localStorage.getItem('token'))
		},
		methods: {
			submitForm() {
				axios.post('/api/register', this.form)
                 .then((res) => {
                     localStorage.setItem('token', res.data.token);
                     this.$fire({
						title: 'Done',
						text: res.data.message,
						type: "success",
						timer: 5000
					});
                    axios.defaults.headers.common['Authorization'] = `Bearer `+res.data.token;
                    this.$router.push('/');
                 })
                 .catch((error) => {
                 	let errorMsg = error.response.data.errors.toString();
                    this.$fire({
						title: error.response.data.message,
						text: errorMsg,
						type: "error",
						timer: 5000
					});
                 });
			}
		}
	}

</script>
