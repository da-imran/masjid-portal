import { useState } from 'react';
import { useLogin, useNotify, useTranslate } from 'react-admin';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CircularProgress from '@mui/material/CircularProgress';
import TextField from '@mui/material/TextField';

const LoginPage = () => {
    const [loading, setLoading] = useState(false);
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const translate = useTranslate();
    const notify = useNotify();
    const login = useLogin();

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);

        login({ username: email, password })
            .then(() => {
                setLoading(false);
            })
            .catch((error) => {
                setLoading(false);
                notify(
                    typeof error === 'string'
                        ? error
                        : typeof error === 'undefined' || !error.message
                        ? 'ra.auth.sign_in_error'
                        : error.message,
                    {
                        type: 'warning',
                        messageArgs: {
                            _: typeof error === 'string'
                                ? error
                                : error && error.message
                                ? error.message
                                : undefined,
                        },
                    }
                );
            });
    };

    return (
        <form onSubmit={handleSubmit} noValidate style={{
            display: 'flex',
            flexDirection: 'column',
            minHeight: '100vh',
            alignItems: 'center',
            justifyContent: 'flex-start',
            background: 'url(/images/banner_1.png)',
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
        }}>
            <Card style={{ minWidth: 300, marginTop: '6em' }}>
                <div style={{ margin: '1em', display: 'flex', justifyContent: 'center' }}>
                    <h2>Admin Portal</h2>
                </div>
                <div style={{ padding: '0 1em 1em 1em' }}>
                    <TextField
                        id="email"
                        name="email"
                        label={translate('ra.auth.email')}
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        fullWidth
                        style={{ marginTop: '1em' }}
                        autoComplete="email"
                        autoFocus
                    />
                    <TextField
                        id="password"
                        name="password"
                        label={translate('ra.auth.password')}
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        fullWidth
                        style={{ marginTop: '1em' }}
                        autoComplete="current-password"
                    />
                </div>
                <CardActions style={{ padding: '0 1em 1em 1em' }}>
                    <Button
                        variant="contained"
                        type="submit"
                        color="primary"
                        disabled={loading}
                        fullWidth
                    >
                        {loading && <CircularProgress size={25} thickness={2} />}
                        {translate('ra.auth.sign_in')}
                    </Button>
                </CardActions>
            </Card>
        </form>
    );
};

export default LoginPage;
