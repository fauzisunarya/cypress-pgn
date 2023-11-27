import { Container } from "@mui/material";
import { Helmet } from "react-helmet-async";

export default function Page(props: any){
    return(
        <>
      <Helmet>
        <title> {props.title}</title>
      </Helmet>

      <Container> 
        {props.children}
      </Container>
      </>
    )
}