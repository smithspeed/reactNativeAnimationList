import React, { useCallback } from 'react'
import { View, Text, StyleSheet, Colo, Dimensions } from 'react-native';
import Animated, { useAnimatedStyle, useSharedValue } from 'react-native-reanimated';
import ColorPicker from '../components/ColorPicker';

const COLORS = [
    'red',
    'purple',
    'blue',
    'cyan',
    'green',
    'yellow',
    'orange',
    'black',
    'white'
];

const BACKGROUND_COLOR = 'rgba(0,0,0,0.9)';

const {width} = Dimensions.get('window');

const PICKER_WIDTH = width * 0.9;

const CIRCLE_SIZE = width * 0.8;

export default function ColorPickerScreen() {

    const pickedColor = useSharedValue(COLORS[0]);

    const onColorChanged = useCallback((color) => {
        'worklet';
        //console.log(color);

        pickedColor.value = color;

    },[]);

    const animatedStyle = useAnimatedStyle(() => {

        return {
            backgroundColor : pickedColor.value
        }

    });


    return (
        <>
            <View 
                style={styles.topContainer}
            >
                <Animated.View style={[styles.circle, animatedStyle] }/>
            </View>

            <View 
                style={styles.bottomContainer}
            >
                <ColorPicker 
                    colors={COLORS}
                    style={styles.gradient}
                    start={{x:0, y: 0}}
                    end={{x: 1, y: 0}}
                    maxWidth={PICKER_WIDTH}
                    onColorChanged={onColorChanged}
                />
            </View>
        </>
        
    )
}

const styles = StyleSheet.create({
    topContainer : {
        flex : 3,
        backgroundColor : BACKGROUND_COLOR,
        alignItems : 'center',
        justifyContent : 'center'
        
    },
    bottomContainer : {
        flex : 1,
        backgroundColor : BACKGROUND_COLOR,
        alignItems : 'center',
        justifyContent : 'center'
    },
    circle : {
        width : CIRCLE_SIZE,
        height : CIRCLE_SIZE,
        borderRadius : CIRCLE_SIZE / 2,
    },
    gradient : { 
        height : 30, 
        width : PICKER_WIDTH,
        borderRadius:20
    }
}); 