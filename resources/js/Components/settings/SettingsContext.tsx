import { ReactNode, createContext, useEffect, useContext, useMemo, useCallback, useState } from 'react';
// hooks
import useLocalStorage from '../../hooks/useLocalStorage';
//
import { defaultSettings } from './config';
import { personalization, token } from '@/config';
import { SettingsContextProps } from './types';
import { defaultPreset, getPresets, presetsOption } from './presets';
import { jwtDecode } from '@/auth/utils';

// ----------------------------------------------------------------------

const initialState: SettingsContextProps = {
  ...defaultSettings,
  // Mode
  onToggleMode: () => {},
  onChangeMode: () => {},
  // Direction
  onToggleDirection: () => {},
  onChangeDirection: () => {},
  onChangeDirectionByLang: () => {},
  // Layout
  onChangeLayout: () => {},
  // Contrast
  onToggleContrast: () => {},
  onChangeContrast: () => {},
  // Color
  onChangeColorPresets: () => {},
  presetsColor: defaultPreset,
  presetsOption: [],
  // Stretch
  onToggleStretch: () => {},
  // Reset
  onResetSetting: () => {},
};

// ----------------------------------------------------------------------

export const SettingsContext = createContext(initialState);

export const useSettingsContext = () => {
  const context = useContext(SettingsContext);

  if (!context) throw new Error('useSettingsContext must be use inside SettingsProvider');

  return context;
};

// ----------------------------------------------------------------------

type SettingsProviderProps = {
  children: ReactNode;
};

export function SettingsProvider({ children }: SettingsProviderProps) {
  const [settings, setSettings] = useLocalStorage('settings', defaultSettings);

  const langStorage = typeof window !== 'undefined' ? localStorage.getItem('i18nextLng') : '';

  const isArabic = langStorage === 'ar';

  useEffect(() => {
    if (isArabic) {
      onChangeDirectionByLang('ar');
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isArabic]);

  // Mode
  const onToggleMode = useCallback(() => {
    const themeMode = settings.themeMode === 'light' ? 'dark' : 'light';
    setSettings({ ...settings, themeMode });
  }, [setSettings, settings]);

  const onChangeMode = useCallback(
    (event: React.ChangeEvent<HTMLInputElement>) => {
      const themeMode = event.target.value;
      setSettings({ ...settings, themeMode });
    },
    [setSettings, settings]
  );

  // Direction
  const onToggleDirection = useCallback(() => {
    const themeDirection = settings.themeDirection === 'rtl' ? 'ltr' : 'rtl';
    setSettings({ ...settings, themeDirection });
  }, [setSettings, settings]);

  const onChangeDirection = useCallback(
    (event: React.ChangeEvent<HTMLInputElement>) => {
      const themeDirection = event.target.value;
      setSettings({ ...settings, themeDirection });
    },
    [setSettings, settings]
  );

  const onChangeDirectionByLang = useCallback(
    (lang: string) => {
      const themeDirection = lang === 'ar' ? 'rtl' : 'ltr';
      setSettings({ ...settings, themeDirection });
    },
    [setSettings, settings]
  );

  // Layout
  const onChangeLayout = useCallback(
    (event: React.ChangeEvent<HTMLInputElement>) => {
      const themeLayout = event.target.value;
      setSettings({ ...settings, themeLayout });
    },
    [setSettings, settings]
  );

  // Contrast
  const onToggleContrast = useCallback(() => {
    const themeContrast = settings.themeContrast === 'default' ? 'bold' : 'default';
    setSettings({ ...settings, themeContrast });
  }, [setSettings, settings]);

  const onChangeContrast = useCallback(
    (event: React.ChangeEvent<HTMLInputElement>) => {
      const themeContrast = event.target.value;
      setSettings({ ...settings, themeContrast });
    },
    [setSettings, settings]
  );

  // Color
  const onChangeColorPresets = useCallback(
    (event: React.ChangeEvent<HTMLInputElement>) => {
      const themeColorPresets = event.target.value;
      setSettings({ ...settings, themeColorPresets });
    },
    [setSettings, settings]
  );

  // Stretch
  const onToggleStretch = useCallback(() => {
    const themeStretch = !settings.themeStretch;
    setSettings({ ...settings, themeStretch });
  }, [setSettings, settings]);

  // Reset
  const onResetSetting = useCallback(() => {
    setSettings(defaultSettings);
  }, [setSettings]);

  const value = useMemo(
    () => ({
      ...settings,
      // Mode
      onToggleMode,
      onChangeMode,
      // Direction
      onToggleDirection,
      onChangeDirection,
      onChangeDirectionByLang,
      // Layout
      onChangeLayout,
      // Contrast
      onChangeContrast,
      onToggleContrast,
      // Stretch
      onToggleStretch,
      // Color
      onChangeColorPresets,
      presetsOption,
      presetsColor: getPresets(settings.themeColorPresets),
      // Reset
      onResetSetting,
    }),
    [
      settings,
      // Mode
      onToggleMode,
      onChangeMode,
      // Direction
      onToggleDirection,
      onChangeDirection,
      onChangeDirectionByLang,
      // Layout
      onChangeLayout,
      onChangeContrast,
      // Contrast
      onToggleContrast,
      // Stretch
      onToggleStretch,
      // Color
      onChangeColorPresets,
      // Reset
      onResetSetting,
    ]
  );
  
    const [configLoaded, setConfigLoaded] = useState(false);
  
    const handleauthApplication = async () => {
    try {
      if (!configLoaded) {
        let application_config = sessionStorage.getItem("application_config");
        let style_config = sessionStorage.getItem("style_config");

        if (application_config && style_config) {
          let application_config_object = JSON.parse(application_config);
          let style_config_object = JSON.parse(style_config);

          if(application_config_object){
            personalization.application = application_config_object.name;
            personalization.logo = application_config_object.image_logo;
            personalization.logo_full = application_config_object.image_logo_name;
            personalization.style_name = style_config_object;
          }

        } else {
          const tokenStorage: any = localStorage.getItem('accessToken') ? localStorage.getItem('accessToken') : token;
          if (tokenStorage) {
            const payload = jwtDecode(tokenStorage);
            const config = JSON.parse(payload.config);

            personalization.application = config.application.name;
            personalization.logo = config.application.image_logo;
            personalization.logo_full = config.application.image_logo_name;
            let style_name = config.style ? config.style : 'default';
            personalization.style_name = style_name;

            sessionStorage.setItem("application_config", JSON.stringify(config.application));
            sessionStorage.setItem("style_config", JSON.stringify(style_name));
            console.log(style_name);
            console.log('qqqqq');
          }
        }
      }
    } catch (error) {

    }
    
        setConfigLoaded(true);
    // setShowApp(true);
    // setShowLoading(false)
  };

    useEffect(() => {
        handleauthApplication();
    }, []);

    // Call Style from CDN
    let applicationStyle = typeof window !== 'undefined' ? personalization.style_name : "default";

    useEffect(() => {
    // Create a link element for the stylesheet
    const link = document.createElement('link');

    // Set the link attributes
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = 'https://cdn.neuronworks.co.id/dev/mui/doorv3-'+applicationStyle+'.css'; // Replace with your CDN URL

    // Append the link element to the document's head
    document.head.appendChild(link);

    // Clean up by removing the link element when the component unmounts
    return () => {
        document.head.removeChild(link);
        };
    }, [applicationStyle]);
  

  return <SettingsContext.Provider value={value}>{children}</SettingsContext.Provider>;
}
