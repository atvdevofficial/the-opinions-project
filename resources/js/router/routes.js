import LoginComponent from '../components/LoginComponent.vue'

import FeedComponent from '../components/FeedComponent.vue'
import ChatsComponent from '../components/ChatsComponent.vue'
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
        path: '/chats',
        name: 'chats',
        component: ChatsComponent
    },
    {
        path: '/chats/:id',
        name: 'chat',
        component: ChatComponent,
    },
]

export default routes
