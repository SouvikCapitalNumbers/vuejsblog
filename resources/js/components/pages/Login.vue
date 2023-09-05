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
			                  <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
			                  <form method="POST" class="needs-validation" novalidate="" autocomplete="off" v-on:submit.prevent="submitForm">
			                      <div class="mb-3">
			                          <label class="mb-2 text-muted" for="email">E-Mail Address</label>
			                          <input type="email" class="form-control" v-model="form.email" value="" required autofocus />
			                      </div>

			                      <div class="mb-3">
			                          <div class="mb-2 w-100">
			                              <label class="text-muted" for="password">Password</label>
			                              <router-link to="/forgot" class="float-end">
			                                  Forgot Password?
			                              </router-link>
			                          </div>
			                          <input type="password" class="form-control" v-model="form.password" required />
			                      </div>

			                      <div class="d-flex align-items-center">
			                          <button type="submit" class="btn btn-primary ms-auto">
			                              Login
			                          </button>
			                      </div>
			                  </form>
			              </div>
			              <div class="card-footer py-3 border-0">
			                  <div class="text-center">Don't have an account? <router-link to="/register" class="text-dark">Create One</router-link></div>
			              </div>
			          </div>
			      </div>
			  </div>
			</div>
		</section>


	</div>
</template>


<script>
	import Header from '../common/Header.vue'

	export default {
		name: "Login",
		components: { Header },
		data: () => {
			return {
				form: {
					email: '',
					password: ''
				}
			}
		},
		created: () => {
			document.title = 'Log In'
		},
		methods: {
			submitForm() {
				axios.post('/api/login', this.form)
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

</script>
