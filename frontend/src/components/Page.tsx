import { Container } from "@mui/material";
import { useEffect } from "react";
import { Helmet } from "react-helmet-async";
import { useLocation } from "react-router";
import { useSettingsContext } from "./settings";

export default function Page(props: any){
  const { themeStretch } = useSettingsContext();
    return(
        <>
      <Helmet>
        <title> {props.title}</title>
      </Helmet>
      {props.container && (
        <Container maxWidth={themeStretch ? false : 'xl'}> 
          {props.children}
        </Container>
      )}
      {!props.container && (
        <> 
          {props.children}
        </>
      )}
      </>
    )
}