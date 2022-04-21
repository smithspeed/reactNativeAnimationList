import React, { useState } from 'react'
import { View, Text, StyleSheet, Switch, Dimensions } from 'react-native'
import Animated, { interpolate, interpolateColor, useAnimatedStyle, useDerivedValue, useSharedValue, withTiming } from 'react-native-reanimated';


const Colors = {
    dark : {
        background: '#1E1E1E',
        circle : '#252525',
        text : '#F8F8F8'
    },
    light : {
        background : '#F8F8F8',
        circle : '#FFF',
        text : '#1E1E1E',
    }
}

const SWITCH_TRACK_COLOR = {
    true : 'rgba(256, 0, 256, 0.2)',
    false : 'rgba(0, 0, 0, 0.1)'
}

export default function InterpolateWithColor() {

    const [theme, setTheme] = useState('light');

    //const progress = useSharedValue(0);
    const progress = useDerivedValue(() => {
        return theme === 'dark' ? withTiming(1) : withTiming(0)
    },[theme])

    const animatedStyle = useAnimatedStyle(() => {

        const backgroundColor = interpolateColor(
            progress.value,
            [0, 1],
            [Colors.light.background, Colors.dark.background]
        );
        return {
            backgroundColor
        };
    });

    const animatedCircleStyle = useAnimatedStyle(() => {

        const backgroundColor = interpolateColor(
            progress.value,
            [0, 1],
            [Colors.light.circle, Colors.dark.circle]
        );
        
        return { backgroundColor };

    });

    return (
        <Animated.View style={[styles.container,animatedStyle]}>
            <Animated.View style={[styles.circle, animatedCircleStyle]}>
                <Switch 
                    value={theme === 'dark'}
                    onValueChange={(toggled) => {
                        setTheme(toggled ? 'dark' : 'light');
                    }}
                    trackColor = {SWITCH_TRACK_COLOR}
                    thumbColor ={'violet'}
                />
            </Animated.View>
            
        </Animated.View>
    )
}

const SIZE = Dimensions.get('window').width * 0.7;

const styles = StyleSheet.create({
    container : {
        flex: 1,
        backgroundColor: 'grey',
        alignItems: 'center',
        justifyContent:'center'
    },
    circle : {
        width : SIZE,
        height : SIZE,
        backgroundColor : '#FFF',
        alignItems:'center',
        justifyContent : 'center',
        borderRadius : SIZE / 2,
        shadowColor: 'black',
        shadowOpacity: 0.26,
        shadowOffset: { 
            width: 0, 
            height: 2
        },
        shadowRadius: 10,
        elevation: 8,
    }
});