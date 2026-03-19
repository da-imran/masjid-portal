import { useRedirect } from 'react-admin';

export const PermissionShow = () => {
    const redirect = useRedirect();
    redirect('list', 'permissions');
    return null;
};
