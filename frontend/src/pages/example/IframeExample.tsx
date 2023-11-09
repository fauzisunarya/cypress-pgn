// @mui
import { Container } from '@mui/material';
import { useEffect, useState } from 'react';
// components
import Page from 'src/components/Page';
import useIframe from 'src/hooks/useIframe';

// ----------------------------------------------------------------------

export default function IframeExample() {
  const { iframeProps, sendIframeMessage } = useIframe('http://localhost:3006/form-example');
  
  return (
    <>
      <Page title="Articles">
        <Container>
          <iframe {...iframeProps} onLoad={() => sendIframeMessage('token', localStorage.getItem('accessToken'))} />
        </Container>
      </Page>
    </>
  );
}