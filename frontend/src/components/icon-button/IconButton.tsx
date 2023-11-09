import { IconButton as IconButtonMUI  } from "@mui/material";
import Iconify from "../iconify/Iconify";

export default function IconButton(props: any) {
    let width = 20;
    switch(props.size){
        case 'small' :
            width = 16;break;
        case 'medium' :
            width = 21;break;
        case 'large' :
            width = 28;break;
    }
    return (
        <IconButtonMUI  {...props}> <Iconify icon={props.icon} width={width}/> </IconButtonMUI>
    )
}