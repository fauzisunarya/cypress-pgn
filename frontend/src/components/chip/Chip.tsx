import { Chip as ChipMUI  } from "@mui/material";
import Iconify from "../iconify/Iconify";

export default function Chip(props: any) {
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
        <ChipMUI sx={{ borderRadius:'6px', ...props.sx }} {...props}/>
    )
}