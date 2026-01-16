import {
    List,
    Datagrid,
    TextField,
    BooleanField,
    EditButton,
    ShowButton,
    useTranslate,
} from 'react-admin';
import { Chip } from '@mui/material';

export const RoleList = () => {
    const translate = useTranslate();

    return (
        <List>
            <Datagrid rowClick="show">
                <TextField source="id" label="ID" />
                <TextField source="name" label="Name" />
                <TextField source="slug" label="Slug" />
                <TextField source="description" label="Description" />
                <BooleanField source="is_default" label="Default" />
                <EditButton />
                <ShowButton />
            </Datagrid>
        </List>
    );
};
