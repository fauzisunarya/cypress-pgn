import { Container } from "@mui/material";
import { useEffect } from "react";
import { Helmet } from "react-helmet-async";
import { useLocation } from "react-router";

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