import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

// import Home from './components/pages/Home.vue'
// import Login from './components/pages/Login.vue'
// import Register from './components/pages/Register.vue'
// import Forgot from './components/pages/Forgot.vue'
// import MyBlogs from './components/pages/MyBlogs.vue'
// import Blog from './components/pages/Blog.vue'
// import BlogCreate from './components/pages/BlogCreate.vue'


function lazyLoad(view){
	return() => import(`./components/pages/${view}.vue`)
}



const routes = [
	{
		path: "/",
		component: lazyLoad('Home') 
	},
	{
		path: "/login",
		component: lazyLoad('Login') 
	},
	{
		path: "/register",
		component: lazyLoad('Register')
	},
	{
		path: "/forgot",
		component: lazyLoad('Forgot')
	},
	{
		path: "/my-blogs",
		component: lazyLoad('MyBlogs')
	},
	{
		path: "/search",
		component: lazyLoad('Search')
	},
	{
		path: "/blog/create",
		component: lazyLoad('BlogCreate')
	},
	{
		path: "/blog/edit/:id",
		component: lazyLoad('BlogCreate')
	},
	{
		path: "/blog/:id",
		component: lazyLoad('Blog')
	}
]

export default new Router({
	mode: 'history',
	routes
})