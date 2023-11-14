import { Post } from 'src/models/post';
import { ref } from 'vue';

const posts = ref();

const refresh = () => {
  Post.allPostInDashboard()
  .then(
    (response)=>{
      posts.value = response
    }
  )
};
refresh();
export { posts, refresh };
