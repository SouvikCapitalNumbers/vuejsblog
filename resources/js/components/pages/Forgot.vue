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
			                  <h1 class="fs-4 card-title fw-bold mb-4">Retrive Password</h1>
			                  <form method="POST" class="needs-validation" novalidate="" autocomplete="off">

			                  		<div class="mb-3">
									  	<label class="mb-2 text-muted" for="email">E-Mail Address</label>
									  	<input type="email" class="form-control" name="email" value="" required />
									</div>

			                      	<div class="d-flex align-items-center">
			                          	<button type="submit" class="btn btn-primary ms-auto">
			                              Get Password
			                          	</button>
			                      	</div>
			                  	</form>
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
		name: "Forgot",
		components: { Header }
	}

</script>
