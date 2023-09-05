<template>
	<div>
		<nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light">
			<div class="container-fluid">
			  <router-link class="navbar-brand mb-0 h1" to="/" exact>BLOG</router-link>
			  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			     <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse">
			     <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			        <li class="nav-item">
			           <router-link class="nav-link" active-class="active" aria-current="page" to="/" exact>Home</router-link>
			        </li>
			        <li class="nav-item" v-if="!userdata">
			           <router-link class="nav-link" active-class="active" to="/login">Login</router-link>
			        </li>
		        	<li class="nav-item dropdown" v-if="userdata">
						<button class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{{ userdata.name }}
						</button>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<router-link class="dropdown-item" to="/my-blogs">My Blogs</router-link>
							<router-link class="dropdown-item" to="/blog/create">Create Blogs</router-link>
							<button class="dropdown-item" v-on:click="dologout">Logout</button>
						</div>
					</li>
			     </ul>
			     <div class="form-inline" role="search" v-if="!searchbarhide">
			        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" @click="callsearchpage"/>
			     </div>
			  </div>
			</div>
		</nav>
		<Loader :loading="loading"></Loader>
	</div>
</template>

<script>
	import Loader from './Loader.vue'
	import axios from 'axios'
	export default {
		name: "Header",
		props: ['userdata', 'loading', 'searchbarhide'],
		components: {Loader},
		methods: {
			dologout(){
				localStorage.setItem('token', '');
				axios.defaults.headers.common['Authorization'] = ``;
				this.$router.push('/login');
			},
			callsearchpage(){
				this.$router.push('/search');
			}
		}
	}
</script>