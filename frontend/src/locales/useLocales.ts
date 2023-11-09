import { useTranslation } from 'react-i18next';
// components
import { useSettingsContext } from '../components/settings';
//
import { allLangs, defaultLang } from './config';
import axiosContent from 'src/utils/axios';

// ----------------------------------------------------------------------

export default function useLocales() {
  const { i18n, t: translate } = useTranslation();

  const { onChangeDirectionByLang } = useSettingsContext();

  const langStorage = typeof window !== 'undefined' ? localStorage.getItem('i18nextLng') : '';

  const currentLang = allLangs.find((_lang) => _lang.value === langStorage) || defaultLang;

  const handleChangeLanguage = (newlang: string) => {
    i18n.changeLanguage(newlang);
    onChangeDirectionByLang(newlang);
    axiosContent.defaults.headers.common['Accept-Language'] = newlang;
  };

  return {
    onChangeLang: handleChangeLanguage,
    translate: (text: any, options?: any) => {
      let text_alias = text.split(' ').join('_');
      let translated = translate(text_alias.toLowerCase(), options);
      let is_success_translated = translated != text_alias.toLowerCase();
      if(!is_success_translated){
        return text;
      }
      return translated;
    },
    currentLang,
    allLangs,
  };
}
