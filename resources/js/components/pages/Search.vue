<template>
	<div>
		<Header :userdata="userdata" :loading="loading" :searchbarhide="searchbarhide"></Header>
		<div class="container mt-5">
			<div class="row mb-4">
				<form class="form-inline">
					<input class="form-control mr-sm-2 col-12" type="search" placeholder="Search" aria-label="Search" v-on:keyup="searchChange" v-model="searchKeyword">
				</form>
			</div>
			<Blogbox :blogs="blogs" is_admin="true"></Blogbox>
			<div class="row mt-5" v-if="returnData.links && blogs.length">
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
		name: "Search",
		components: { Header, Blogbox },
		data: () => {
			return {
				userdata: null,
			    loading: false,
			    blogs: [],
			    returnData: [],
			    searchbarhide: true,
			    searchKeyword: ''
			}
		},
		created(){
			document.title = 'Search';
			this.loadPage();
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
					this.loading = false;
				});
			},

			loadpager(indx) {
				let loader = this.returnData.links[indx];
				if(loader.url)
				{
					let url = loader.url.split("api").pop();
					axios.get('/api'+url)
					.then((res) => {
						this.blogs = res.data.data;
						this.returnData = res.data;
					});
				}
			},

			searchChange() {
				if(this.searchKeyword.length > 3)
				{
					axios.get('/api/blog/search/'+this.searchKeyword)
					.then((res) => {
						this.blogs = res.data.data;
						this.returnData = res.data;
					});
				}
				else
				{
					this.blogs = [];
					this.returnData = [];
				}
			}
		}
	}

</script>