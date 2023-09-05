<template>
	<div>
		<Header :userdata="userdata" :loading="loading"></Header>
		<div class="container mt-5">
			<Blogbox :blogs="blogs"></Blogbox>
			<div class="row mt-5" v-if="returnData.links">
				<nav aria-label="Page navigation">
					<ul class="pagination">
						<li class="page-item" v-for="(link, index) in returnData.links" :class="{ active: link.active }"><button class="page-link" @click="loadpager(index)">{{ link.label | stripHTML }}</button></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from '../common/Header.vue'
	import Blogbox from '../common/Blogbox.vue'

	export default {
		name: "Home",
		components: { Header, Blogbox },
		data: () => {
			return {
				userdata: null,
			    loading: false,
			    blogs: [],
			    returnData: []
			}
		},
		created(){
			document.title = 'All Blogs';
			this.loadPage();
			this.loadAllBlogs();
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
					this.userdata = null;
					this.loading = false;
				});
			},

			loadAllBlogs() {
				this.loading = true;
				axios.get('/api/blog/allblog')
				.then((res) => {
					this.loading = false;
					this.blogs = res.data.data;
					this.returnData = res.data;
				});
			},

			loadpager(indx) {
				let loader = this.returnData.links[indx];
				if(loader.url)
				{
					let url = loader.url.split("api").pop();
					this.loading = true;
					axios.get('/api'+url)
					.then((res) => {
						this.loading = false;
						this.blogs = res.data.data;
						this.returnData = res.data;
					});
				}
			}
		}
	}

</script>