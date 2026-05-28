<?php

namespace arielmejiadev\LeafletForLaravel\Enums;

enum MaterialDesignIcon: string implements GlyphEnum
{
    // Places & Points of Interest
    case Home = 'home';
    case OfficeBuilding = 'office-building';
    case Store = 'store';
    case Hospital = 'hospital-building';
    case School = 'school';
    case Church = 'church';
    case Mosque = 'mosque';
    case Synagogue = 'synagogue';
    case Bank = 'bank';
    case Castle = 'castle';
    case Warehouse = 'warehouse';
    case Factory = 'factory';
    case Garage = 'garage';
    case Barn = 'barn';
    case HospitalBox = 'hospital-box';
    case Library = 'library';
    case Stadium = 'stadium';
    case Lighthouse = 'lighthouse';
    case Fountain = 'fountain';
    case Tent = 'tent';

    // Map & Navigation
    case Earth = 'earth';
    case MapMarker = 'map-marker';
    case MapMarkerRadius = 'map-marker-radius';
    case MapMarkerCheck = 'map-marker-check';
    case MapMarkerPlus = 'map-marker-plus';
    case MapMarkerMinus = 'map-marker-minus';
    case MapMarkerAlert = 'map-marker-alert';
    case Map = 'map';
    case Crosshairs = 'crosshairs';
    case CrosshairsGps = 'crosshairs-gps';
    case Compass = 'compass';
    case Flag = 'flag';
    case FlagTriangle = 'flag-triangle';
    case Anchor = 'anchor';
    case SignDirection = 'sign-direction';
    case Magnify = 'magnify';
    case Binoculars = 'binoculars';
    case Navigation = 'navigation';

    // Transportation
    case Car = 'car';
    case Bus = 'bus';
    case Train = 'train';
    case Airplane = 'airplane';
    case Ferry = 'ferry';
    case Bike = 'bike';
    case Taxi = 'taxi';
    case Truck = 'truck';
    case Motorbike = 'motorbike';
    case Helicopter = 'helicopter';
    case Subway = 'subway';
    case Van = 'van-utility';
    case Walk = 'walk';
    case Run = 'run';
    case Tractor = 'tractor';
    case RocketLaunch = 'rocket-launch';
    case SailBoat = 'sail-boat';
    case Kayaking = 'kayaking';
    case Parking = 'parking';
    case GasStation = 'gas-station';
    case EvStation = 'ev-station';
    case Road = 'road';

    // Food & Drink
    case Coffee = 'coffee';
    case Silverware = 'silverware';
    case SilverwareFork = 'silverware-fork';
    case SilverwareSpoon = 'silverware-spoon';
    case Beer = 'beer';
    case GlassWine = 'glass-wine';
    case GlassCocktail = 'glass-cocktail';
    case Pizza = 'pizza';
    case Hamburger = 'hamburger';
    case FoodApple = 'food-apple';
    case FoodDrumstick = 'food-drumstick';
    case IceCream = 'ice-cream';
    case Cake = 'cake';
    case Cookie = 'cookie';
    case Chili = 'chili-mild';
    case Carrot = 'carrot';
    case Corn = 'corn';
    case Egg = 'egg';
    case CupWater = 'cup-water';
    case Bottle = 'bottle-wine';

    // Health & Emergency
    case Ambulance = 'ambulance';
    case MedicalBag = 'medical-bag';
    case Stethoscope = 'stethoscope';
    case Pill = 'pill';
    case Needle = 'needle';
    case Thermometer = 'thermometer';
    case Heartbeat = 'heart-pulse';
    case HospitalMarker = 'hospital-marker';
    case Bandage = 'bandage';
    case WheelchairAccessibility = 'wheelchair-accessibility';
    case Lungs = 'lungs';
    case Brain = 'brain';
    case Tooth = 'tooth';
    case EmoticonSick = 'emoticon-sick';

    // Nature & Weather
    case Tree = 'tree';
    case Terrain = 'terrain';
    case Water = 'water';
    case Leaf = 'leaf';
    case Flower = 'flower';
    case Sprout = 'sprout';
    case WeatherSunny = 'weather-sunny';
    case Cloud = 'cloud';
    case WeatherRainy = 'weather-rainy';
    case WeatherSnowy = 'weather-snowy';
    case WeatherWindy = 'weather-windy';
    case ImageFilterDrama = 'image-filter-drama';
    case Snowflake = 'snowflake';
    case Umbrella = 'umbrella';
    case BeachUmbrella = 'beach';
    case ThermometerHigh = 'thermometer-high';
    case ThermometerLow = 'thermometer-low';
    case Paw = 'paw';
    case Dog = 'dog';
    case Cat = 'cat';
    case Horse = 'horse-variant';
    case Rabbit = 'rabbit';
    case Fish = 'fish';
    case Bird = 'bird';
    case Bee = 'bee';
    case Butterfly = 'butterfly';

    // Sports & Recreation
    case Soccer = 'soccer';
    case Basketball = 'basketball';
    case Football = 'football';
    case Baseball = 'baseball';
    case Golf = 'golf';
    case Tennis = 'tennis';
    case Volleyball = 'volleyball';
    case Swim = 'swim';
    case Hiking = 'hiking';
    case Ski = 'ski';
    case Snowboard = 'snowboard';
    case Skate = 'skate';
    case WeightLifter = 'weight-lifter';
    case ControllerClassic = 'controller-classic';
    case ChessKnight = 'chess-knight';
    case Dice5 = 'dice-5';
    case PuzzleOutline = 'puzzle-outline';
    case Billiards = 'billiards';
    case Karate = 'karate';
    case Yoga = 'yoga';

    // Commerce & Finance
    case Cart = 'cart';
    case ShoppingOutline = 'shopping-outline';
    case CurrencyUsd = 'currency-usd';
    case CashMultiple = 'cash-multiple';
    case CreditCardOutline = 'credit-card-outline';
    case Bitcoin = 'bitcoin';
    case Wallet = 'wallet';
    case PiggyBank = 'piggy-bank';
    case CashRegister = 'cash-register';
    case Receipt = 'receipt';
    case Sale = 'sale';
    case TagMultiple = 'tag-multiple';

    // People
    case Account = 'account';
    case AccountGroup = 'account-group';
    case AccountTie = 'account-tie';
    case AccountHardHat = 'account-hard-hat';
    case Doctor = 'doctor';
    case AccountSchool = 'account-school';
    case HumanChild = 'human-child';
    case BabyCarriage = 'baby-carriage';
    case HumanMale = 'human-male';
    case HumanFemale = 'human-female';
    case AccountCowboyHat = 'account-cowboy-hat';
    case Ninja = 'ninja';
    case HandshakeOutline = 'handshake-outline';

    // Education & Work
    case BookOpenVariant = 'book-open-variant';
    case NewspaperVariant = 'newspaper-variant';
    case SchoolOutline = 'school-outline';
    case Briefcase = 'briefcase';
    case Toolbox = 'toolbox';
    case Wrench = 'wrench';
    case Hammer = 'hammer';
    case Brush = 'brush';
    case Pencil = 'pencil';
    case Palette = 'palette';
    case RulerSquare = 'ruler-square';
    case BookmarkOutline = 'bookmark-outline';
    case NoteText = 'note-text';
    case ClipboardText = 'clipboard-text';

    // Technology
    case Laptop = 'laptop';
    case Cellphone = 'cellphone';
    case Monitor = 'monitor';
    case Server = 'server-network';
    case DatabaseOutline = 'database-outline';
    case SatelliteVariant = 'satellite-variant';
    case Antenna = 'antenna';
    case Robot = 'robot';
    case Chip = 'chip';
    case PowerPlug = 'power-plug';
    case Battery = 'battery';
    case Signal = 'signal';
    case Printer = 'printer';
    case Television = 'television';

    // Communication
    case Phone = 'phone';
    case Email = 'email';
    case Wifi = 'wifi';
    case Bell = 'bell';
    case Camera = 'camera';
    case Music = 'music';
    case Headphones = 'headphones';
    case VolumeHigh = 'volume-high';
    case MessageText = 'message-text';
    case Forum = 'forum';

    // Signs & Symbols
    case Information = 'information';
    case InformationOutline = 'information-outline';
    case Alert = 'alert';
    case AlertCircle = 'alert-circle';
    case Check = 'check';
    case CheckCircle = 'check-circle';
    case Close = 'close';
    case CloseCircle = 'close-circle';
    case Plus = 'plus';
    case PlusCircle = 'plus-circle';
    case MinusCircle = 'minus-circle';
    case HelpCircle = 'help-circle';
    case Cancel = 'cancel';
    case ShieldCheck = 'shield-check';
    case Lock = 'lock';
    case LockOpen = 'lock-open';
    case Key = 'key';
    case ThumbUp = 'thumb-up';
    case ThumbDown = 'thumb-down';
    case Star = 'star';
    case Heart = 'heart';
    case Flash = 'flash';
    case Fire = 'fire';

    // Miscellaneous
    case Gift = 'gift';
    case Trophy = 'trophy';
    case Crown = 'crown';
    case Diamond = 'diamond-stone';
    case Medal = 'medal';
    case Clock = 'clock';
    case Calendar = 'calendar';
    case BagSuitcase = 'bag-suitcase';
    case Recycle = 'recycle';
    case Delete = 'delete';
    case Glasses = 'glasses';
    case Snowman = 'snowman';
    case WizardHat = 'wizard-hat';
    case PartyPopper = 'party-popper';

    public function prefix(): string
    {
        return 'mdi';
    }

    public function glyph(): string
    {
        return $this->value;
    }

    public static function cssUrl(): string
    {
        return 'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css';
    }
}
