<template>
	<div>
		<Header :userdata="userdata" :loading="loading"></Header>
		<main role="main" class="container mt-5" v-if="blogData">
		   <div class="row">
		      <div class="col-md-8 blog-main">
		        <div class="blog-post">
		            <h2 class="blog-post-title">{{ blogData.title }}</h2>
		            <p class="blog-post-meta">{{ timeSince(blogData.created_at) }} ago by <a  v-if="typeof blogData.user !== 'undefined'">{{ blogData.user.name }}</a></p>
		         </div><!-- /.blog-post -->
		         <div class="blog-post">
		         	
		         </div>
		         <div class="blog-post mt-5" v-html="blogData.description"></div><!-- /.blog-post -->
		      </div><!-- /.blog-main -->
		      <aside class="col-md-4 blog-sidebar">
		         <div class="p-3 mb-3 bg-light rounded">
		            <img :src="blogData.image" class="img-fluid" alt="Responsive image" v-if="blogData.image">
		            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Placeholder_view_vector.svg/681px-Placeholder_view_vector.svg.png?20220519031949" class="img-fluid" alt="Responsive image" v-if="!blogData.image">
		         </div>
		         <div class="p-3">
		            <h4 class="font-italic">Related Blogs</h4>
		            <ol class="list-unstyled mb-0">
		               <li v-for="related in related_blog"><router-link :to="'/blog/'+related.id">{{related.title}}</router-link></li>
		            </ol>
		         </div>
		      </aside><!-- /.blog-sidebar -->
		   </div><!-- /.row -->
		</main>
	</div>
</template>

<script>
	import Header from '../common/Header.vue'
	import moment from 'moment';

	export default {
		name: "Blog",
		components: { Header },
		data: () => {
			return {
				userdata: null,
				loading: false,
				blogData: [],
				related_blog: []
			}
		},
		created(){
			document.title = 'Blog Page';
			this.loadPage();
			if(this.$route.params.id)
			{
				this.getBlogData(this.$route.params.id);
			}
		},
		beforeRouteUpdate(to, from, next) {
			this.getBlogData(to.params.id);
			next();
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

			getBlogData(id) {
				this.loading = true;
				axios.get('/api/blog/show/'+id)
				.then((res) => {
					document.title = res.data.data.title;
					this.blogData = res.data.data;
					this.related_blog = res.data.related_blog;
					this.loading = false;
					this.buttonText = 'Edit';
				})
				.catch((error) => {
					this.$fire({
						title: 'Error',
						text: 'No blog found',
						type: "error",
						timer: 5000
					});
					this.$router.push('/');
					this.loading = false;
				});
			},

			timeSince(date) {

				var from = moment(date);
				var to = moment();

				var seconds = moment.duration(to.diff(from)).asSeconds();

				var interval = seconds / 31536000;

				if (interval > 1) {
					return Math.floor(interval) + " years";
				}
				interval = seconds / 2592000;
				if (interval > 1) {
					return Math.floor(interval) + " months";
				}
				interval = seconds / 86400;
				if (interval > 1) {
					return Math.floor(interval) + " days";
				}
				interval = seconds / 3600;
				if (interval > 1) {
					return Math.floor(interval) + " hours";
				}
				interval = seconds / 60;
				if (interval > 1) {
					return Math.floor(interval) + " minutes";
				}
				return Math.floor(seconds) + " seconds";
			}
		}
	}

</script>