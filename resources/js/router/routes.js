import ExampleComponent from '../components/ExampleComponent.vue'
import ChatListComponent from '../components/ChatListComponent.vue'
import ChatComponent from '../components/ChatComponent.vue'

const routes = [
    {
        path: '/sample',
        name: 'example',
        component: ExampleComponent
    },
    {
        path: '/chat-list',
        name: 'chatList',
        component: ChatListComponent
    },
    {
        path: '/chat/username',
        name: 'chatList',
        component: ChatComponent
    },
]

export default routes
