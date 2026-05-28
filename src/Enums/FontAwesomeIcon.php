<?php

namespace arielmejiadev\LeafletForLaravel\Enums;

enum FontAwesomeIcon: string implements GlyphEnum
{
    // Places & Points of Interest
    case Home = 'home';
    case Building = 'building';
    case Store = 'store';
    case StoreAlt = 'store-alt';
    case Hotel = 'hotel';
    case Hospital = 'hospital';
    case School = 'school';
    case University = 'university';
    case Church = 'church';
    case Mosque = 'mosque';
    case Synagogue = 'synagogue';
    case PlaceOfWorship = 'place-of-worship';
    case Landmark = 'landmark';
    case Monument = 'monument';
    case Warehouse = 'warehouse';
    case Industry = 'industry';
    case City = 'city';
    case Campground = 'campground';
    case Archway = 'archway';
    case ToriiGate = 'torii-gate';
    case Igloo = 'igloo';
    case Dungeon = 'dungeon';

    // Map & Navigation
    case Globe = 'globe';
    case MapMarker = 'map-marker-alt';
    case MapPin = 'map-pin';
    case Map = 'map';
    case LocationArrow = 'location-arrow';
    case Compass = 'compass';
    case Flag = 'flag';
    case Road = 'road';
    case Anchor = 'anchor';
    case Directions = 'directions';
    case Route = 'route';
    case Search = 'search';
    case Binoculars = 'binoculars';
    case StreetView = 'street-view';

    // Transportation
    case Car = 'car';
    case Bus = 'bus';
    case Train = 'train';
    case Plane = 'plane';
    case Ship = 'ship';
    case Bicycle = 'bicycle';
    case Taxi = 'taxi';
    case Truck = 'truck';
    case Motorcycle = 'motorcycle';
    case Helicopter = 'helicopter';
    case Subway = 'subway';
    case ShuttleVan = 'shuttle-van';
    case Walking = 'walking';
    case Running = 'running';
    case Biking = 'biking';
    case Tractor = 'tractor';
    case SpaceShuttle = 'space-shuttle';
    case TruckPickup = 'truck-pickup';
    case Caravan = 'caravan';
    case Parking = 'parking';
    case GasPump = 'gas-pump';
    case ChargingStation = 'charging-station';

    // Food & Drink
    case Coffee = 'coffee';
    case Utensils = 'utensils';
    case Beer = 'beer';
    case WineGlass = 'wine-glass';
    case WineGlassAlt = 'wine-glass-alt';
    case Cocktail = 'cocktail';
    case PizzaSlice = 'pizza-slice';
    case Hamburger = 'hamburger';
    case Hotdog = 'hotdog';
    case IceCream = 'ice-cream';
    case Cookie = 'cookie';
    case DrumstickBite = 'drumstick-bite';
    case PepperHot = 'pepper-hot';
    case AppleAlt = 'apple-alt';
    case Carrot = 'carrot';
    case Lemon = 'lemon';
    case Fish = 'fish';
    case Egg = 'egg';
    case BreadSlice = 'bread-slice';
    case Cheese = 'cheese';
    case Bacon = 'bacon';
    case CandyCane = 'candy-cane';

    // Health & Emergency
    case Ambulance = 'ambulance';
    case Medkit = 'medkit';
    case FirstAid = 'first-aid';
    case Stethoscope = 'stethoscope';
    case ClinicMedical = 'clinic-medical';
    case Heartbeat = 'heartbeat';
    case Pills = 'pills';
    case Syringe = 'syringe';
    case NotesMedical = 'notes-medical';
    case Tooth = 'tooth';
    case Brain = 'brain';
    case Lungs = 'lungs';
    case Crutch = 'crutch';
    case Wheelchair = 'wheelchair';
    case Biohazard = 'biohazard';
    case Radiation = 'radiation';

    // Nature & Weather
    case Tree = 'tree';
    case Mountain = 'mountain';
    case Water = 'water';
    case Leaf = 'leaf';
    case Seedling = 'seedling';
    case Sun = 'sun';
    case Cloud = 'cloud';
    case CloudRain = 'cloud-rain';
    case CloudSun = 'cloud-sun';
    case Snowflake = 'snowflake';
    case Wind = 'wind';
    case Rainbow = 'rainbow';
    case Umbrella = 'umbrella';
    case UmbrellaBeach = 'umbrella-beach';
    case TemperatureHigh = 'temperature-high';
    case TemperatureLow = 'temperature-low';
    case Paw = 'paw';
    case Dog = 'dog';
    case Cat = 'cat';
    case Horse = 'horse';
    case Dove = 'dove';
    case Feather = 'feather';
    case Spider = 'spider';
    case Frog = 'frog';
    case Dragon = 'dragon';

    // Sports & Recreation
    case Futbol = 'futbol';
    case BasketballBall = 'basketball-ball';
    case FootballBall = 'football-ball';
    case BaseballBall = 'baseball-ball';
    case GolfBall = 'golf-ball';
    case VolleyballBall = 'volleyball-ball';
    case TableTennis = 'table-tennis';
    case BowlingBall = 'bowling-ball';
    case SwimmingPool = 'swimming-pool';
    case Hiking = 'hiking';
    case Skiing = 'skiing';
    case Snowboarding = 'snowboarding';
    case Skating = 'skating';
    case Dumbbell = 'dumbbell';
    case Gamepad = 'gamepad';
    case Chess = 'chess';
    case Dice = 'dice';
    case PuzzlePiece = 'puzzle-piece';

    // Commerce & Finance
    case ShoppingCart = 'shopping-cart';
    case ShoppingBag = 'shopping-bag';
    case DollarSign = 'dollar-sign';
    case MoneyBill = 'money-bill';
    case MoneyBillWave = 'money-bill-wave';
    case CreditCard = 'credit-card';
    case Coins = 'coins';
    case Wallet = 'wallet';
    case PiggyBank = 'piggy-bank';
    case CashRegister = 'cash-register';
    case Receipt = 'receipt';
    case HandHoldingUsd = 'hand-holding-usd';

    // People
    case User = 'user';
    case Users = 'users';
    case UserTie = 'user-tie';
    case UserMd = 'user-md';
    case UserNurse = 'user-nurse';
    case UserGraduate = 'user-graduate';
    case UserSecret = 'user-secret';
    case UserAstronaut = 'user-astronaut';
    case Child = 'child';
    case Baby = 'baby';
    case BabyCarriage = 'baby-carriage';
    case PeopleCarry = 'people-carry';
    case HandsHelping = 'hands-helping';
    case HardHat = 'hard-hat';

    // Education & Work
    case Book = 'book';
    case BookOpen = 'book-open';
    case Newspaper = 'newspaper';
    case GraduationCap = 'graduation-cap';
    case Briefcase = 'briefcase';
    case Tools = 'tools';
    case Wrench = 'wrench';
    case Hammer = 'hammer';
    case PaintBrush = 'paint-brush';
    case PaintRoller = 'paint-roller';
    case PencilAlt = 'pencil-alt';
    case Palette = 'palette';
    case Ruler = 'ruler';
    case DraftingCompass = 'drafting-compass';
    case ChalkboardTeacher = 'chalkboard-teacher';
    case Bookmark = 'bookmark';

    // Technology
    case Laptop = 'laptop';
    case LaptopCode = 'laptop-code';
    case MobileAlt = 'mobile-alt';
    case Desktop = 'desktop';
    case Server = 'server';
    case Database = 'database';
    case Satellite = 'satellite';
    case SatelliteDish = 'satellite-dish';
    case BroadcastTower = 'broadcast-tower';
    case Robot = 'robot';
    case Microchip = 'microchip';
    case Plug = 'plug';
    case BatteryFull = 'battery-full';
    case Signal = 'signal';
    case Print = 'print';
    case Tv = 'tv';

    // Communication
    case Phone = 'phone';
    case Envelope = 'envelope';
    case Wifi = 'wifi';
    case Bell = 'bell';
    case Camera = 'camera';
    case Music = 'music';
    case Headphones = 'headphones';
    case VolumeUp = 'volume-up';

    // Signs & Symbols
    case Info = 'info';
    case InfoCircle = 'info-circle';
    case ExclamationTriangle = 'exclamation-triangle';
    case ExclamationCircle = 'exclamation-circle';
    case Check = 'check';
    case CheckCircle = 'check-circle';
    case Times = 'times';
    case TimesCircle = 'times-circle';
    case Plus = 'plus';
    case PlusCircle = 'plus-circle';
    case Minus = 'minus';
    case MinusCircle = 'minus-circle';
    case Question = 'question';
    case QuestionCircle = 'question-circle';
    case Ban = 'ban';
    case ShieldAlt = 'shield-alt';
    case Lock = 'lock';
    case Unlock = 'unlock';
    case Key = 'key';
    case ThumbsUp = 'thumbs-up';
    case ThumbsDown = 'thumbs-down';
    case Star = 'star';
    case Heart = 'heart';
    case Bolt = 'bolt';
    case Fire = 'fire';

    // Miscellaneous
    case Gift = 'gift';
    case Trophy = 'trophy';
    case Crown = 'crown';
    case Gem = 'gem';
    case Medal = 'medal';
    case Clock = 'clock';
    case Calendar = 'calendar';
    case CalendarAlt = 'calendar-alt';
    case Suitcase = 'suitcase';
    case SuitcaseRolling = 'suitcase-rolling';
    case Recycle = 'recycle';
    case Trash = 'trash';
    case Glasses = 'glasses';
    case Snowman = 'snowman';
    case Magic = 'magic';

    public function prefix(): string
    {
        return 'fas';
    }

    public function glyph(): string
    {
        return $this->value;
    }

    public static function cssUrl(): string
    {
        return 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
    }
}
