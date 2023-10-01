import { useEffect, useRef, useState } from 'react';

function useIframe(src?: string) {
  const iframeRef = useRef<any>(null);
  const [iframeProps, setIframe] = useState({});
  const [data, setData] = useState<any>({});

  useEffect(() => {
    // Create an iframe element and set its attributes
    const iframe = {
        "src" : src,
        "width" : '100%',
        "height" : '500',
        "title" : 'Iframe',
        "ref" : iframeRef,
        "style" : {
            "border": "none"
        }
    };
    setIframe(iframe);

    // Function to send a post message to the iframe

    // Add event listener to handle incoming messages from the iframe
    const handleMessage = (event: any) => {
      // Handle the received message here
      setData(event.data);
    };

    window.addEventListener('message', handleMessage);

    // Clean up the event listener when the component unmounts
    return () => {
      window.removeEventListener('message', handleMessage);
    };
  }, [src]);

  return {
    data: data,
    iframeProps: iframeProps,
    sendIframeMessage: (key: string,value: string | Object | Array<any>) => {
      if (iframeRef.current) {
        var parameter: any = {};
        parameter[key] = value;
        iframeRef.current.contentWindow.postMessage(parameter,"*");
      }
    },
  };
}

export default useIframe;
