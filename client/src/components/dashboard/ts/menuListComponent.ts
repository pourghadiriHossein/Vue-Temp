import{ mdiSearchWeb ,mdiHome, mdiNote, mdiNoteMultiple, mdiAccountGroup} from '@quasar/extras/mdi-v7';

type menuItem = {name: string, route: string, icon: string};

function menu() {
  return [
    { name: 'Search', route: 'searchPost', icon: mdiSearchWeb },
    { name: 'Dashboard', route: 'dashboard', icon: mdiHome },
    { name: 'My Posts', route: 'myPosts', icon: mdiNote },
    { name: 'All Posts', route: 'allPosts', icon: mdiNoteMultiple },
    { name: 'All Users', route: 'allUsers', icon: mdiAccountGroup },
  ] as menuItem[];
}

export const accessMenu = menu();
