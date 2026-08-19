/**
 * Main entry point for the theme's JavaScript.
 * you must add any functions for every javascript file to the import statement below.
 */

import { Modals } from "./functions/modals";
import { register } from "swiper/element/bundle";
import { SubMenuDesktop } from "./functions/subMenuDesktop";
import { SubMenuMobile } from "./functions/subMenuMobile";
import { Htmx } from "./functions/htmx";
import { FaqTabs, FaqCard } from "./functions/faq";
import videoCover from "./functions/videoCover";
import { VideoPlyr } from "./functions/plyr";
import { shareBtn } from "./functions/shareBtn";
import { Accordion } from "./functions/accordion";
import { SearchPage } from "./functions/search";
import { ThemeToggle } from "./functions/themeToggle";
import { PrimaryButton } from "./functions/primaryButton";
import { BlogArchive } from "./functions/blogArchive";
import { ProductArchive } from "./functions/productArchive";
import { ProjectArchive } from "./functions/projectArchive";
import { ProductSingle } from "./functions/productSingle";
import fancybox from "./modules/fancybox";
import { ProductGallery } from "./functions/productGallery";
import { StatCount } from "./functions/statCount";

Modals();
register();
SubMenuDesktop();
SubMenuMobile();
Htmx();
FaqTabs();
FaqCard();
videoCover();
VideoPlyr();
shareBtn();
Accordion();
SearchPage();
ThemeToggle();
PrimaryButton();
BlogArchive();
ProductArchive();
ProjectArchive();
ProductSingle();
ProductGallery();
StatCount();
fancybox();
