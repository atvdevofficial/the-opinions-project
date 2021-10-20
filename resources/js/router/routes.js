import LoginComponent from '../components/LoginComponent.vue'

import FeedComponent from '../components/FeedComponent.vue'
import ChatListComponent from '../components/ChatListComponent.vue'
import ChatComponent from '../components/ChatComponent.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: LoginComponent
    },
    {
        path: '/feed',
        name: 'feed',
        component: FeedComponent
    },
    {
        path: '/chat-list',
        name: 'chatList',
        component: ChatListComponent
    },
    {
        path: '/chat/username',
        name: 'chat',
        component: ChatComponent
    },
]

export default routes
