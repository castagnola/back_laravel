/**
 * All Routes
 * @type {*[]}
 */

let routes = [
    {
        path: '/login',
        component: require('./components/OAuth/LoginComponent').default,
        meta: {
            forVisitors: true
        }
    },
    {
        path: '/register',
        component: require('./components/OAuth/RegisterComponent').default,
        meta: {
            forVisitors: true
        }
    },
    {
        path: '/users',
        component: require('./components/management/UsersComponent.vue').default,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/roles',
        component: require('./components/management/RolesComponent').default,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/profile',
        component: require('./components/management/ProfileComponent').default,
        meta: {
            forAuth: true
        }
    },
    {
        path: '/dashboard',
        component: require('./components/layouts/masterComponent').default,
        meta: {
            forAuth: true
        }
    },
    // {
    //     path: '/users/:id/edit',
    //     component: require('./components/ProfileComponent').default
    // },
]

export default routes;