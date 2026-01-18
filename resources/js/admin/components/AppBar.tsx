import { AppBar, UserMenu, useTranslate, useLogout, useGetIdentity } from 'react-admin';
import Typography from '@mui/material/Typography';
import { Box, MenuItem } from '@mui/material';
import ExitIcon from '@mui/icons-material/ExitToApp';

const CustomUserMenu = () => {
    const translate = useTranslate();
    const logout = useLogout();
    const { isLoading: identityLoading } = useGetIdentity();

    return identityLoading ? null : (
        <UserMenu>
            <MenuItem onClick={() => { window.location.href = '/'; }}>
                Back to Site
            </MenuItem>
            <MenuItem onClick={logout}>
                <ExitIcon fontSize="small" sx={{ mr: 1 }} />
                {translate('ra.auth.logout')}
            </MenuItem>
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
