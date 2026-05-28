# Icons

The package offers three ways to customize marker icons: **built-in color markers**, **custom image icons**, and **glyph icons** (icon fonts rendered directly on the pin).

<IconsDemo />

## Built-in color markers

The easiest way to use colored markers. Seven colors are available out of the box:

```php
use arielmejiadev\LeafletForLaravel\Icon;

Icon::color('blue')
Icon::color('green')
Icon::color('red')
Icon::color('orange')
Icon::color('violet')
Icon::color('gold')
Icon::color('yellow')
```

Shorthand when adding a marker — just pass the color name as a string:

```php
->marker(34.1341, -118.3215, fn ($pin) => $pin->icon('blue')) // Hollywood Sign
```

## Custom icons from storage

Use your own icons from `public/storage`:

```php
$icon = Icon::fromStorage('icons/custom-pin.png')
    ->size(32, 48)
    ->anchor(16, 48);

->marker(43.8791, -103.4591, fn ($pin) => $pin->icon($icon)) // Mount Rushmore
```

## Custom icons from any URL

```php
$icon = new Icon('https://example.com/my-icon.png');
```

Or use the static helper:

```php
$icon = LeafletMap::icon('https://example.com/my-icon.png');
```

## Icon customization methods

| Method | Description | Default |
|---|---|---|
| `size(int $width, int $height)` | Icon dimensions in pixels | `[25, 41]` |
| `anchor(int $x, int $y)` | The pixel coordinates of the icon's "tip" | `[12, 41]` |
| `popupAnchor(int $x, int $y)` | Where the popup opens relative to the anchor | `[1, -34]` |
| `shadow(string $url, ?int $w, ?int $h)` | Custom shadow image | Leaflet default |
| `shadowAnchor(int $x, int $y)` | Shadow position relative to the icon | `[12, 41]` |

### Full custom icon example

```php
use arielmejiadev\LeafletForLaravel\Icon;
use arielmejiadev\LeafletForLaravel\LeafletMap;

$customIcon = Icon::fromStorage('pins/museum.png')
    ->size(40, 40)
    ->anchor(20, 40)
    ->popupAnchor(0, -35)
    ->shadow('https://example.com/shadow.png', 41, 41)
    ->shadowAnchor(12, 41);

$map = LeafletMap::of('nyc-museums')
    ->marker(40.7794, -73.9632, fn ($pin) => $pin
        ->popup('The Metropolitan Museum of Art')
        ->icon($customIcon)
    )
    ->marker(40.7614, -73.9776, fn ($pin) => $pin
        ->popup('Museum of Modern Art (MoMA)')
        ->icon(Icon::color('green'))
    )
    ->fitBounds();
```

---

## Glyph icons (icon fonts on markers)

Display icon-font glyphs directly on map markers using the [Leaflet.Icon.Glyph](https://github.com/Leaflet/Leaflet.Icon.Glyph) plugin. The package ships with two built-in libraries:

| Library | Enum | Icons | Reference |
|---|---|---|---|
| [Font Awesome 5 Free](https://fontawesome.com/v5/search?m=free&s=solid) | `FontAwesomeIcon` | ~190 solid icons | [Cheatsheet](https://fontawesome.com/v5/cheatsheet/free/solid) |
| [Material Design Icons](https://pictogrammers.com/library/mdi/) | `MaterialDesignIcon` | ~190 icons | [Icon search](https://pictogrammers.com/library/mdi/) |

You can also use **any icon font library** by implementing the `GlyphEnum` interface (see [Custom icon enums](#custom-icon-enums) below).

### Setup

No extra setup is required. When you use glyph markers, `@leafletMap` **automatically** includes the required CSS and JavaScript for the icon libraries you use.

```blade
<head>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
```

> **Optional:** If you prefer loading the icon font CSS in `<head>` for faster rendering, you can still use `@leafletGlyphStyles`:
> ```blade
> @leafletGlyphStyles(\arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon::class)
> ```
> Or for multiple libraries:
> ```blade
> @leafletGlyphStyles([
>     \arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon::class,
>     \arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon::class,
> ])
> ```

### Basic usage

Use the `glyph()` method on a marker instead of `icon()`:

```php
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('dc-landmarks')
    ->marker(38.8977, -77.0365, fn (Marker $pin) => $pin
        ->popup('<b>The White House</b>')
        ->glyph(FontAwesomeIcon::Landmark)
    )
    ->marker(38.8881, -77.0199, fn (Marker $pin) => $pin
        ->popup('<b>Smithsonian Museum</b>')
        ->glyph(FontAwesomeIcon::University)
    )
    ->fitBounds();
```

Pass a second argument to set the glyph color:

```php
->glyph(FontAwesomeIcon::Star, 'gold')
```

### Font Awesome examples

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;

$map = LeafletMap::of('chicago-spots')
    ->marker(41.8796, -87.6237, fn (Marker $pin) => $pin
        ->popup('<b>Art Institute of Chicago</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Landmark)->markerColor('orange'))
    )
    ->marker(41.8827, -87.6233, fn (Marker $pin) => $pin
        ->popup('<b>Millennium Park Grill</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Utensils)->markerColor('red'))
    )
    ->marker(41.8950, -87.6174, fn (Marker $pin) => $pin
        ->popup('<b>Northwestern Memorial Hospital</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Hospital)->markerColor('green'))
    )
    ->marker(41.8808, -87.6265, fn (Marker $pin) => $pin
        ->popup('<b>Palmer House Hotel</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Hotel)->markerColor('violet'))
    )
    ->fitBounds();
```

### Material Design Icons examples

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;

$map = LeafletMap::of('la-spots')
    ->marker(33.9534, -118.3390, fn (Marker $pin) => $pin
        ->popup('<b>SoFi Stadium</b>')
        ->glyph(GlyphIcon::materialDesign(MaterialDesignIcon::Stadium)->markerColor('blue'))
    )
    ->marker(34.0780, -118.4741, fn (Marker $pin) => $pin
        ->popup('<b>Getty Center</b>')
        ->glyph(GlyphIcon::materialDesign(MaterialDesignIcon::Bank)->markerColor('green'))
    )
    ->marker(34.1341, -118.3215, fn (Marker $pin) => $pin
        ->popup('<b>Hollywood Sign</b>')
        ->glyph(GlyphIcon::materialDesign(MaterialDesignIcon::Antenna)->markerColor('orange'))
    )
    ->marker(34.1184, -118.3004, fn (Marker $pin) => $pin
        ->popup('<b>Griffith Observatory</b>')
        ->glyph(GlyphIcon::materialDesign(MaterialDesignIcon::Lighthouse)->markerColor('gold'))
    )
    ->fitBounds();
```

### Mixing Font Awesome and Material Design Icons

You can mix both libraries in the same map. The required CSS for each library is loaded automatically:

```php
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;

$map = LeafletMap::of('central-park')
    ->marker(40.7829, -73.9654, fn (Marker $pin) => $pin
        ->popup('Central Park — Font Awesome tree')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Tree)->markerColor('green'))
    )
    ->marker(40.7812, -73.9665, fn (Marker $pin) => $pin
        ->popup('Central Park — Material Design tree')
        ->glyph(GlyphIcon::materialDesign(MaterialDesignIcon::Tree)->markerColor('green'))
    )
    ->fitBounds();
```

### Marker pin colors

Use `markerColor()` to change the pin background color:

```php
->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Store)->markerColor('red'))
```

Available colors: `blue`, `green`, `red`, `orange`, `violet`, `gold`, `yellow`, `grey`, `black`.

### Advanced GlyphIcon configuration

For full control, build a `GlyphIcon` instance:

```php
use arielmejiadev\LeafletForLaravel\GlyphIcon;

->marker(47.6205, -122.3493, fn (Marker $pin) => $pin // Space Needle, Seattle
    ->glyph(
        GlyphIcon::fontAwesome(FontAwesomeIcon::Coffee)
            ->color('white')        // glyph color
            ->size('14px')          // CSS font-size
            ->glyphAnchor(0, -7)    // offset from marker center
            ->markerColor('red')    // pin background color
    )
)
```

### GlyphIcon API reference

| Method | Description | Default |
|---|---|---|
| `color(string $color)` | Glyph color | `'white'` |
| `size(string $size)` | CSS font-size | `'12px'` (FA), `'20px'` (MDI) |
| `glyphAnchor(int $x, int $y)` | Offset from marker center | Plugin default |
| `markerColor(string $color)` | Pin background color | `'blue'` |
| `iconSize(int $w, int $h)` | Marker icon dimensions | `[25, 41]` |
| `iconAnchor(int $x, int $y)` | Marker anchor point | `[12, 41]` |
| `popupAnchor(int $x, int $y)` | Popup position | `[1, -34]` |

### Available Font Awesome icons

Browse all icons: [fontawesome.com/v5/cheatsheet/free/solid](https://fontawesome.com/v5/cheatsheet/free/solid)

**Places:** `Home`, `Building`, `Store`, `StoreAlt`, `Hotel`, `Hospital`, `School`, `University`, `Church`, `Mosque`, `Synagogue`, `PlaceOfWorship`, `Landmark`, `Monument`, `Warehouse`, `Industry`, `City`, `Campground`, `Archway`, `ToriiGate`, `Igloo`, `Dungeon`

**Map & Navigation:** `Globe`, `MapMarker`, `MapPin`, `Map`, `LocationArrow`, `Compass`, `Flag`, `Road`, `Anchor`, `Directions`, `Route`, `Search`, `Binoculars`, `StreetView`

**Transportation:** `Car`, `Bus`, `Train`, `Plane`, `Ship`, `Bicycle`, `Taxi`, `Truck`, `Motorcycle`, `Helicopter`, `Subway`, `ShuttleVan`, `Walking`, `Running`, `Biking`, `Tractor`, `SpaceShuttle`, `TruckPickup`, `Caravan`, `Parking`, `GasPump`, `ChargingStation`

**Food & Drink:** `Coffee`, `Utensils`, `Beer`, `WineGlass`, `WineGlassAlt`, `Cocktail`, `PizzaSlice`, `Hamburger`, `Hotdog`, `IceCream`, `Cookie`, `DrumstickBite`, `PepperHot`, `AppleAlt`, `Carrot`, `Lemon`, `Fish`, `Egg`, `BreadSlice`, `Cheese`, `Bacon`, `CandyCane`

**Health:** `Ambulance`, `Medkit`, `FirstAid`, `Stethoscope`, `ClinicMedical`, `Heartbeat`, `Pills`, `Syringe`, `NotesMedical`, `Tooth`, `Brain`, `Lungs`, `Crutch`, `Wheelchair`, `Biohazard`, `Radiation`

**Nature & Weather:** `Tree`, `Mountain`, `Water`, `Leaf`, `Seedling`, `Sun`, `Cloud`, `CloudRain`, `CloudSun`, `Snowflake`, `Wind`, `Rainbow`, `Umbrella`, `UmbrellaBeach`, `TemperatureHigh`, `TemperatureLow`, `Paw`, `Dog`, `Cat`, `Horse`, `Dove`, `Feather`, `Spider`, `Frog`, `Dragon`

**Sports:** `Futbol`, `BasketballBall`, `FootballBall`, `BaseballBall`, `GolfBall`, `VolleyballBall`, `TableTennis`, `BowlingBall`, `SwimmingPool`, `Hiking`, `Skiing`, `Snowboarding`, `Skating`, `Dumbbell`, `Gamepad`, `Chess`, `Dice`, `PuzzlePiece`

**Commerce:** `ShoppingCart`, `ShoppingBag`, `DollarSign`, `MoneyBill`, `MoneyBillWave`, `CreditCard`, `Coins`, `Wallet`, `PiggyBank`, `CashRegister`, `Receipt`, `HandHoldingUsd`

**People:** `User`, `Users`, `UserTie`, `UserMd`, `UserNurse`, `UserGraduate`, `UserSecret`, `UserAstronaut`, `Child`, `Baby`, `BabyCarriage`, `PeopleCarry`, `HandsHelping`, `HardHat`

**Education & Work:** `Book`, `BookOpen`, `Newspaper`, `GraduationCap`, `Briefcase`, `Tools`, `Wrench`, `Hammer`, `PaintBrush`, `PaintRoller`, `PencilAlt`, `Palette`, `Ruler`, `DraftingCompass`, `ChalkboardTeacher`, `Bookmark`

**Technology:** `Laptop`, `LaptopCode`, `MobileAlt`, `Desktop`, `Server`, `Database`, `Satellite`, `SatelliteDish`, `BroadcastTower`, `Robot`, `Microchip`, `Plug`, `BatteryFull`, `Signal`, `Print`, `Tv`

**Communication:** `Phone`, `Envelope`, `Wifi`, `Bell`, `Camera`, `Music`, `Headphones`, `VolumeUp`

**Signs & Symbols:** `Info`, `InfoCircle`, `ExclamationTriangle`, `ExclamationCircle`, `Check`, `CheckCircle`, `Times`, `TimesCircle`, `Plus`, `PlusCircle`, `Minus`, `MinusCircle`, `Question`, `QuestionCircle`, `Ban`, `ShieldAlt`, `Lock`, `Unlock`, `Key`, `ThumbsUp`, `ThumbsDown`, `Star`, `Heart`, `Bolt`, `Fire`

**Miscellaneous:** `Gift`, `Trophy`, `Crown`, `Gem`, `Medal`, `Clock`, `Calendar`, `CalendarAlt`, `Suitcase`, `SuitcaseRolling`, `Recycle`, `Trash`, `Glasses`, `Snowman`, `Magic`

### Available Material Design icons

Browse all icons: [pictogrammers.com/library/mdi](https://pictogrammers.com/library/mdi/)

**Places:** `Home`, `OfficeBuilding`, `Store`, `Hospital`, `School`, `Church`, `Mosque`, `Synagogue`, `Bank`, `Castle`, `Warehouse`, `Factory`, `Garage`, `Barn`, `HospitalBox`, `Library`, `Stadium`, `Lighthouse`, `Fountain`, `Tent`

**Map & Navigation:** `Earth`, `MapMarker`, `MapMarkerRadius`, `MapMarkerCheck`, `MapMarkerPlus`, `MapMarkerMinus`, `MapMarkerAlert`, `Map`, `Crosshairs`, `CrosshairsGps`, `Compass`, `Flag`, `FlagTriangle`, `Anchor`, `SignDirection`, `Magnify`, `Binoculars`, `Navigation`

**Transportation:** `Car`, `Bus`, `Train`, `Airplane`, `Ferry`, `Bike`, `Taxi`, `Truck`, `Motorbike`, `Helicopter`, `Subway`, `Van`, `Walk`, `Run`, `Tractor`, `RocketLaunch`, `SailBoat`, `Kayaking`, `Parking`, `GasStation`, `EvStation`, `Road`

**Food & Drink:** `Coffee`, `Silverware`, `SilverwareFork`, `SilverwareSpoon`, `Beer`, `GlassWine`, `GlassCocktail`, `Pizza`, `Hamburger`, `FoodApple`, `FoodDrumstick`, `IceCream`, `Cake`, `Cookie`, `Chili`, `Carrot`, `Corn`, `Egg`, `CupWater`, `Bottle`

**Health:** `Ambulance`, `MedicalBag`, `Stethoscope`, `Pill`, `Needle`, `Thermometer`, `Heartbeat`, `HospitalMarker`, `Bandage`, `WheelchairAccessibility`, `Lungs`, `Brain`, `Tooth`, `EmoticonSick`

**Nature & Weather:** `Tree`, `Terrain`, `Water`, `Leaf`, `Flower`, `Sprout`, `WeatherSunny`, `Cloud`, `WeatherRainy`, `WeatherSnowy`, `WeatherWindy`, `ImageFilterDrama`, `Snowflake`, `Umbrella`, `BeachUmbrella`, `ThermometerHigh`, `ThermometerLow`, `Paw`, `Dog`, `Cat`, `Horse`, `Rabbit`, `Fish`, `Bird`, `Bee`, `Butterfly`

**Sports:** `Soccer`, `Basketball`, `Football`, `Baseball`, `Golf`, `Tennis`, `Volleyball`, `Swim`, `Hiking`, `Ski`, `Snowboard`, `Skate`, `WeightLifter`, `ControllerClassic`, `ChessKnight`, `Dice5`, `PuzzleOutline`, `Billiards`, `Karate`, `Yoga`

**Commerce:** `Cart`, `ShoppingOutline`, `CurrencyUsd`, `CashMultiple`, `CreditCardOutline`, `Bitcoin`, `Wallet`, `PiggyBank`, `CashRegister`, `Receipt`, `Sale`, `TagMultiple`

**People:** `Account`, `AccountGroup`, `AccountTie`, `AccountHardHat`, `Doctor`, `AccountSchool`, `HumanChild`, `BabyCarriage`, `HumanMale`, `HumanFemale`, `AccountCowboyHat`, `Ninja`, `HandshakeOutline`

**Education & Work:** `BookOpenVariant`, `NewspaperVariant`, `SchoolOutline`, `Briefcase`, `Toolbox`, `Wrench`, `Hammer`, `Brush`, `Pencil`, `Palette`, `RulerSquare`, `BookmarkOutline`, `NoteText`, `ClipboardText`

**Technology:** `Laptop`, `Cellphone`, `Monitor`, `Server`, `DatabaseOutline`, `SatelliteVariant`, `Antenna`, `Robot`, `Chip`, `PowerPlug`, `Battery`, `Signal`, `Printer`, `Television`

**Communication:** `Phone`, `Email`, `Wifi`, `Bell`, `Camera`, `Music`, `Headphones`, `VolumeHigh`, `MessageText`, `Forum`

**Signs & Symbols:** `Information`, `InformationOutline`, `Alert`, `AlertCircle`, `Check`, `CheckCircle`, `Close`, `CloseCircle`, `Plus`, `PlusCircle`, `MinusCircle`, `HelpCircle`, `Cancel`, `ShieldCheck`, `Lock`, `LockOpen`, `Key`, `ThumbUp`, `ThumbDown`, `Star`, `Heart`, `Flash`, `Fire`

**Miscellaneous:** `Gift`, `Trophy`, `Crown`, `Diamond`, `Medal`, `Clock`, `Calendar`, `BagSuitcase`, `Recycle`, `Delete`, `Glasses`, `Snowman`, `WizardHat`, `PartyPopper`

### Custom icon enums

Implement the `GlyphEnum` interface to use any icon font library:

```php
use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;

enum BootstrapIcon: string implements GlyphEnum
{
    case House = 'house';
    case Star = 'star';
    case GeoAlt = 'geo-alt';
    case Shop = 'shop';
    case Cup = 'cup-hot';

    public function prefix(): string
    {
        return 'bi';
    }

    public function glyph(): string
    {
        return $this->value;
    }

    public static function cssUrl(): string
    {
        return 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';
    }
}
```

Then use it like any other enum:

```php
->marker(37.8199, -122.4783, fn (Marker $pin) => $pin // Golden Gate Bridge
    ->glyph(BootstrapIcon::GeoAlt)
)
```

The CSS is auto-included when `@leafletMap` renders — no extra Blade directives needed.
