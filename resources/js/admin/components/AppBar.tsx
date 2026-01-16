import { AppBar, UserMenu, MenuItemLink, useTranslate, useLogout, useGetIdentity } from 'react-admin';
import Typography from '@mui/material/Typography';
import { Box } from '@mui/material';
import ExitIcon from '@mui/icons-material/ExitToApp';

const CustomUserMenu = () => {
    const translate = useTranslate();
    const logout = useLogout();
    const { data: identity, isLoading: identityLoading } = useGetIdentity();

    return identityLoading ? null : (
        <UserMenu>
            <MenuItemLink
                to="/"
                primaryText="Back to Site"
                onClick={() => { window.location.href = '/'; }}
            />
            <MenuItemLink
                to="/logout"
                primaryText={translate('ra.auth.logout')}
                onClick={logout}
                leftIcon={<ExitIcon />}
            />
        </UserMenu>
    );
};

export const CustomAppBar = (props: any) => {
    return (
        <AppBar {...props} userMenu={<CustomUserMenu />}>
            <Box
                sx={{
                    flex: 1,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                }}
            >
                <Typography
                    variant="h6"
                    color="inherit"
                    sx={{
                        flex: 1,
                        textOverflow: 'ellipsis',
                        whiteSpace: 'nowrap',
                        overflow: 'hidden',
                    }}
                >
                    Masjid Portal Admin
                </Typography>
            </Box>
        </AppBar>
    );
};
