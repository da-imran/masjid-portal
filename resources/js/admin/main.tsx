import ReactDOM from 'react-dom/client';
import { Admin, Resource, Layout } from 'react-admin';
import { dataProvider } from './dataProvider/dataProvider';
import { authProvider } from './authProvider/authProvider';

// Resources
import { UserList, UserEdit, UserCreate, UserShow } from './resources/users';
import { BeritaList, BeritaEdit, BeritaCreate, BeritaShow } from './resources/berita';
import { KemudahanList, KemudahanEdit, KemudahanCreate, KemudahanShow } from './resources/kemudahan';
import { TakwimList, TakwimEdit, TakwimCreate, TakwimShow } from './resources/takwim';
import { RoleList, RoleEdit, RoleCreate, RoleShow } from './resources/roles';

// Components
import { CustomAppBar } from './components/AppBar';
import LoginPage from './components/LoginPage';

function App() {
    return (
        <Admin
            dataProvider={dataProvider}
            authProvider={authProvider}
            loginPage={LoginPage}
            layout={(props) => <Layout {...props} appBar={CustomAppBar} />}
            requireAuth
        >
            <Resource name="roles" list={RoleList} edit={RoleEdit} create={RoleCreate} show={RoleShow} options={{ label: 'Jenis Pengguna' }} />
            <Resource
                name="users"
                list={UserList}
                edit={UserEdit}
                create={UserCreate}
                show={UserShow}
                options={{ label: 'Pengguna' }}
            />
            <Resource
                name="berita"
                list={BeritaList}
                edit={BeritaEdit}
                create={BeritaCreate}
                show={BeritaShow}
                options={{ label: 'Berita' }}
            />
            <Resource
                name="kemudahan"
                list={KemudahanList}
                edit={KemudahanEdit}
                create={KemudahanCreate}
                show={KemudahanShow}
                options={{ label: 'Kemudahan' }}
            />
            <Resource
                name="takwim"
                list={TakwimList}
                edit={TakwimEdit}
                create={TakwimCreate}
                show={TakwimShow}
                options={{ label: 'Pengumuman' }}
            />
        </Admin>
    );
}

const root = ReactDOM.createRoot(
    document.getElementById('admin-root') as HTMLElement
);

root.render(<App />);
